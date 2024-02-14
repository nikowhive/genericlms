<?php defined('BASEPATH') OR exit('No direct script access allowed');


    class Conversation extends Admin_Controller
    {
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
        public function __construct()
        {
            parent::__construct();
            $this->load->model('usertype_m');
            $this->load->model('classes_m');
            $this->load->model('user_m');
            $this->load->model('alert_m');
            $this->load->model('teacher_m');
            $this->load->model('parents_m');
            $this->load->model('student_m');
            $this->load->model('systemadmin_m');
            $this->load->model('conversation_m');
            $this->load->model('studentrelation_m');
		    $this->load->model("fcmtoken_m");
            $this->load->model('mobile_job_m');
            $language = $this->session->userdata('lang');
            $this->lang->load('conversation', $language);
        }

        protected function rules($reply = false)
        {
            $rules = [
                // [
                //     'field' => 'userGroup',
                //     'label' => $this->lang->line("select_group"),
                //     'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_unique_userGroup'
                // ],
                [
                    'field' => 'message',
                    'label' => $this->lang->line("message"),
                    'rules' => 'trim|xss_clean|callback_unique_message'
                ],
                [
                    'field' => 'subject',
                    'label' => $this->lang->line("subject"),
                    'rules' => 'trim|required|xss_clean|max_length[250]'
                ],
                [
                    'field' => 'attachment',
                    'label' => $this->lang->line("attachment"),
                    'rules' => 'trim|xss_clean|max_length[500]|callback_fileUpload'
                ]
            ];

            if($reply) {
                unset($rules[0], $rules[1], $rules[2]);
                $rules[] = [
                    'field' => 'reply',
                    'label' => 'message',
                    'rules' => 'trim|xss_clean|max_length[500]|callback_unique_message'
                ];
            }
            return $rules;
        }

        public function index()
        {
            $conversations = $this->conversation_m->get_my_conversations();
            $this->data['conversations'] = $this->_conversation($conversations);
            $this->data["subview"] = "conversation/index";
            $this->load->view('_layout_main', $this->data);
        }

        public function draft()
        {
            $conversations = $this->conversation_m->get_my_conversations_draft();
            $this->data['conversations'] = $this->_conversation($conversations, false);
            $this->data["subview"] = "conversation/draft";
            $this->load->view('_layout_main', $this->data);
        }

        public function sent()
        {
            $conversations = $this->conversation_m->get_my_conversations_sent();
            $this->data['conversations'] = $this->_conversation($conversations);
            $this->data["subview"] = "conversation/index";
            $this->load->view('_layout_main', $this->data);
        }

        public function trash()
        {
            $conversations = $this->conversation_m->get_my_conversations_trash();
            $this->data['conversations'] = $this->_conversation($conversations);
            $this->data["subview"] = "conversation/index";
            $this->load->view('_layout_main', $this->data);
        }

        public function draft_send( $id )
        {
            if ( (int) $id ) {
                $conversation = $this->conversation_m->get_conversation($id);
                if ( customCompute($conversation) && $conversation->draft == 1 ) {
                    $this->conversation_m->update_conversation(['draft' => 0], $id);
                    $this->session->set_flashdata('success', $this->lang->line("menu_success"));
                } else {
                    $this->session->set_flashdata('error', 'Draft message not found');
                }
            }
            redirect(base_url("conversation/index"));
        }

        public function create()
        {
            $this->data['headerassets'] = [
                'css' => [
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/editor/jquery-te-1.4.0.css'
                ],
                'js'  => [
                    'assets/select2/select2.js',
                    'assets/editor/jquery-te-1.4.0.min.js',
                ]
            ];
            $userTypeID                 = $this->session->userdata("usertypeID");
            $userID                     = $this->session->userdata("loginuserID");
            $this->data['usertypes']    = $this->conversation_m->get_usertype_by_permission();
            $this->data['classes']      = $this->classes_m->get_classes();
        
             // user UI start

                $schoolyearID = $this->session->userdata('defaultschoolyearID');
				$totalStudent = $this->student_m->get_yearwise_total_students($schoolyearID);
				
				$this->data['totalStudent'] = $totalStudent;
				$this->data['classes'] = $classes = $this->student_m->get_student_count_by_classes($schoolyearID);
				$this->data['employees'] = $employees = $this->student_m->get_user_count_by_usertype();
				 
				$students = $this->student_m->getAllActiveStudents(['active' => 1]);
				$parents = $this->parents_m->getAllActiveParents(['active' => 1]);
		
				$teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
				$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
				$users = $this->user_m->getAllActiveUsers(['active' => 1]);
		
				$usersData = [
					   [
						  'count' => count($teachers) + count($students) + count($parents) + count($systemadmins) + count($users),
						  'name'  => 'All',
						  'type'  => 0
					   ],
					 
					   [
						'count' => count($students),
						'name'  => 'Student',
						'type'  => 3
					   ],
					   [
						'count' => count($parents),
						'name'  => 'Parent',
						'type'  => 4
					   ],
					   [
						'count' => count($systemadmins) + count($users) + count($teachers),
						'name'  => 'Employee',
						'type'  => 11
					   ]
					];
		
				$employeeData = [
					[
						'no' => count($teachers) + count($systemadmins) + count($users),
						'usertype'  => 'All',
						'usertypeID'  => 0
					 ],
					 [
					  'no' => count($systemadmins),
					  'usertype'  => 'Admin',
					  'usertypeID'  => 1
					 ],
					 [
						'no' => count($teachers),
						'usertype'  => 'Teacher',
						'usertypeID'  => 2
					]
				];
				$this->data['usersData'] = $usersData;
		        $this->data['employees'] = array_merge($employeeData,$employees);
				

             // user UI end



            if ( $_POST ) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data['form_validation'] = validation_errors();
                    $this->data['GroupID']         = $this->input->post('userGroup');
                    $this->data["subview"]         = "conversation/add_group";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $postUsers = $this->postUsers();
                    if (customCompute($postUsers)) {
                        $conversation = [
                            'create_date' => date("Y-m-d H:i:s"),
                            'modify_date' => date("Y-m-d H:i:s"),
                            'draft'       => ( ( $this->input->post('submit') == "draft" ) ? 1 : 0 )
                        ];
                        $conversationID = $this->conversation_m->insert_conversation($conversation);

                        $conversationUser = [
                            'conversation_id' => $conversationID,
                            'user_id'         => $userID,
                            'usertypeID'      => $userTypeID,
                            'is_sender'       => 1,
                        ];
                        $this->conversation_m->insert_conversation_user($conversationUser);

                        $conversationMessage = [
                            'user_id'          => $userID,
                            'usertypeID'       => $userTypeID,
                            'subject'          => $this->input->post('subject'),
                            'msg'              => $this->input->post('message'),
                            'create_date'      => date("Y-m-d H:i:s"),
                            'modify_date'      => date("Y-m-d H:i:s"),
                            'start'            => 1,
                            'attach'           => $this->upload_data['file']['attach'],
                            'attach_file_name' => $this->upload_data['file']['attach_file_name']
                        ];
                        
                        //$userGroup = $this->input->post('userGroup');
                        //$users     = $this->_currentUser($userGroup);
                        //print_r($users);die();
                        $this->_messageCreatefor($conversationID, $conversationMessage,$postUsers);

                        $this->session->set_flashdata('success', $this->lang->line("success_msg"));
                        redirect(base_url('conversation/index'));
                    }
                }
            } else {
                $this->data['GroupID'] = 0;
                $this->data["subview"] = "conversation/add_group";
                $this->load->view('_layout_main', $this->data);
            }
        }


        public function postUsers(){

		    $classes = $_POST["bulkClass"]; 
			$bulkEmployees = $_POST["bulkEmployee"];
			$employeeType = $_POST["bulkEmployeeType"]; 
			$formUsers = isset($_POST["users"])?$_POST["users"]:[];
			$send_to_parents = isset($_POST["send_to_parents"])?$_POST["send_to_parents"]:'';

			if($employeeType == 0){

				$teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
				$students = $this->student_m->getAllActiveStudents(['active' => 1]);
				$parents = $this->parents_m->getAllActiveParents(['active' => 1]);
				$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
				$users = $this->user_m->getAllActiveUsers(['active' => 1]);
				$all_users = array_merge($teachers, $students, $parents, $systemadmins, $users);

				$newUsers = [];
				foreach ($all_users as $all_user) {
					$newUsers[] = $all_user['ID'].$all_user['usertypeID'];
				}
				$sall_users = $newUsers;

			}else{
				$dbStudents = [];
				$dbParents = [];
				$dbTeachers = [];
				$dbSystemadmins = [];
				$dbUsers = [];
                $employeeExplodes = explode(',',$employeeType);
				foreach($employeeExplodes as $employeeExplode){
					if($employeeExplode == 3 && $classes == 0){
						$dbStudents = $this->student_m->getAllActiveStudents(['active' => 1]);
					}
					if($employeeExplode == 4 && $classes == 0){
						$dbParents = $this->parents_m->getAllActiveParents(['active' => 1]);
					}
					if($employeeExplode == 11 && $bulkEmployees == 0){
						$dbTeachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
					    $dbSystemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
				        $dbUsers = $this->user_m->getAllActiveUsers(['active' => 1]);
					}
				}
				$all_users = array_merge($dbStudents, $dbParents, $dbTeachers, $dbSystemadmins, $dbUsers);
			
				$allPatents = [];
                if($send_to_parents){
					if(!customCompute($dbParents)){
                       if($classes == 0){
						  $newdbParents = $this->parents_m->getAllActiveParents(['active' => 1]);
					   }else{
						$explodedClasses = explode(',',$classes);
						$newdbParents = $this->parents_m->getAllActiveParentsDetails($explodedClasses);
					}
					if(customCompute($newdbParents)){
						foreach($newdbParents as $newdbParent){
						 $allPatents[] = $newdbParent['ID'] . $newdbParent['usertypeID'];
						}
					 }
					}
				}

			    $newUsers = [];
				foreach ($all_users as $all_user) {
					$newUsers[] = $all_user['ID'].$all_user['usertypeID'];
				}
				$sall_users = array_merge($newUsers,$allPatents);
			}

			$finalUsers = array_unique(array_merge($formUsers,$sall_users));

			return $finalUsers;
	}

        public function view()
        {
            $userTypeID     = $this->session->userdata("usertypeID");
            $userID         = $this->session->userdata("loginuserID");
            $conversationID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $conversationID ) {
                $conversationUser = $this->conversation_m->user_check($conversationID, $userID, $userTypeID);
                if ( customCompute($conversationUser) && $conversationUser->trash != 2 ) {
                    $conversations          = $this->conversation_m->get_conversation_msg_by_id($conversationID);
                    $this->data['messages'] = $this->_conversation($conversations, false, [ 'photo' => 'photo' ]);
                    $this->_alertPost($conversationID);
                    if ( $_POST ) {
                        $rules = $this->rules(true);
                        $this->form_validation->set_rules($rules);
                        if ( $this->form_validation->run() == false ) {
                            $this->session->set_flashdata('error', trim(strip_tags(validation_errors())));
                        } else {
                            $conversationMessage = [
                                'conversation_id'  => $conversationID,
                                'msg'              => $this->input->post('reply'),
                                'user_id'          => $userID,
                                'usertypeID'       => $userTypeID,
                                'create_date'      => date("Y-m-d H:i:s"),
                                'modify_date'      => date("Y-m-d H:i:s"),
                                'attach'           => $this->upload_data['file']['attach'],
                                'attach_file_name' => $this->upload_data['file']['attach_file_name']
                            ];

                            $messageID = $this->conversation_m->insert_conversation_msg($conversationMessage);
                            
                            $this->conversation_m->update_conversation(['modify_date' => date("Y-m-d H:i:s")], $conversationID);
                            
                            $conversation_title = $this->conversation_m->get_single_conversation_msg(['conversation_id' => $conversationID])->subject;

                            $conversation_users = $this->conversation_m->get_conversation_users_by_id($conversationID);
            
                            $all_users = [];
                            foreach($conversation_users as $conversation_user){
                            if($conversation_user->user_id != $this->session->userdata("loginuserID") || $conversation_user->usertypeID != $this->session->userdata("usertypeID") ) {
                                $all_users[] = [
                                    'ID' => $conversation_user->user_id,
                                    'usertypeID' => $conversation_user->usertypeID
                                ];
                            }
                            }

                            $msg = count($all_users) == 1?$conversationMessage['msg']:$this->session->userdata('name').' : '.$conversationMessage['msg'];

                            $array = [
                                'subject' => $this->session->userdata('name'),
                                'msg' => $msg,
                                'users' => $all_users,
                                'conversationID' => $conversationID
                            ];
            
                            $this->addToCronJob($array);

                          
                            if ( $messageID > 0 ) {
                                $this->alert_m->insert_alert([
                                    'itemID'     => $messageID,
                                    "userID"     => $this->session->userdata("loginuserID"),
                                    'usertypeID' => $this->session->userdata('usertypeID'),
                                    'itemname'   => 'message'
                                ]);
                            }
                        }
                        redirect(base_url("conversation/view/$conversationID"));
                    }
                    $this->data["subview"] = "conversation/view";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        }

        public function classCall()
        {
            $classes = $this->classes_m->get_classes();
            echo "<option value='0'>", $this->lang->line("select_class"), "</option>";
            foreach ( $classes as $class ) {
                echo "<option value=\"$class->classesID\">", $class->classes, "</option>";
            }
        }

        public function adminCall()
        {
            $admins = $this->systemadmin_m->get_systemadmin();
            echo "<option value='0'>", $this->lang->line("select_admin"), "</option>";
            foreach ( $admins as $admin ) {
                echo "<option value=\"$admin->systemadminID\">", $admin->name, "</option>";
            }
        }

        public function teacherCall()
        {
            $teachers = $this->teacher_m->get_teacher();
            echo "<option value='0'>", $this->lang->line("select_teacher"), "</option>";
            foreach ( $teachers as $teacher ) {
                echo "<option value=\"$teacher->teacherID\">", $teacher->name, "</option>";
            }
        }

        public function parentCall()
        {
            $parents = $this->parents_m->get_parents();
            echo "<option value='0'>", $this->lang->line("select_parent"), "</option>";
            foreach ( $parents as $parent ) {
                echo "<option value=\"$parent->parentsID\">", $parent->name, "</option>";
            }
        }

        public function userCall()
        {
            $userTypeID         = $this->input->post('id');
            $users = $this->user_m->get_order_by_user([ 'usertypeID' => $userTypeID ]);
            echo "<option value='0'>", $this->lang->line("select_user"), "</option>";
            foreach ( $users as $user ) {
                echo "<option value=\"$user->userID\">", $user->name, "</option>";
            }
        }

        public function studentCall()
        {
            $classesID = $this->input->post('id');
            if ( (int) $classesID ) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                echo "<option value='" . 0 . "'>" . $this->lang->line('select_student') . "</option>";
                $students = $this->studentrelation_m->get_order_by_student([
                    'srclassesID'    => $classesID,
                    'srschoolyearID' => $schoolyearID
                ]);
                foreach ( $students as $key => $student ) {
                    echo "<option value='" . $student->studentID . "'>" . $student->name . "</option>";
                }
            } else {
                echo "<option value='" . 0 . "'>" . $this->lang->line('select_student') . "</option>";
            }
        }

        public function fav_status()
        {
            $id = $this->input->post('id');
            if ( (int) $id ) {
                $conversation = $this->conversation_m->get_conversation($id);
                if(customCompute($conversation)) {
                    if ( $conversation->fav_status == 1 ) {
                        $data['fav_status'] = 0;
                    } else {
                        $data['fav_status'] = 1;
                    }
                }
                $this->conversation_m->update_conversation($data, $id);
                $string = base_url("conversation/index");
                echo $string;
            }
        }

        public function delete_conversation()
        {
            $id = htmlentities(escapeString($this->input->post('id')));
            if ( $id ) {
                $array        = explode(',', $id);
                foreach ( $array as $value ) {
                    $this->conversation_m->trash_conversation(['trash' => 1], $value);
                }
                $this->session->set_flashdata('success', $this->lang->line("deleted"));
            } else {
                $this->session->set_flashdata('error', $this->lang->line("delete_error"));
            }
        }

        public function delete_trash_to_trash()
        {
            $id = htmlentities(escapeString($this->input->post('id')));
            if ( $id ) {
                $array        = explode(',', $id);
                foreach ( $array as $value ) {
                    $this->conversation_m->trash_conversation(['trash' => 2], $value);
                }
                $this->session->set_flashdata('success', $this->lang->line("deleted"));
            } else {
                $this->session->set_flashdata('error', $this->lang->line("delete_error"));
            }
        }

        public function unique_userGroup()
        {
            if ( $this->input->post('userGroup') == 0 ) {
                $this->form_validation->set_message("unique_userGroup", "The %s field is required");
                return false;
            }
            return true;
        }

        public function unique_message($message)
        {
            if ( $message == '' && $_FILES["attachment"]['name'] == "") {
                $this->form_validation->set_message("unique_message", "The %s field is required");
                return false;
            }
            return true;
        }

        public function fileUpload()
        {
            if ( $_FILES["attachment"]['name'] != "" ) {
                $file_name        = $_FILES["attachment"]['name'];
                $uploadPath = 'uploads/attach';
                $random           = random19();
                $makeRandom       = hash('sha512',
                    $random . $this->session->userdata('username') . config_item("encryption_key"));
                $file_name_rename = $makeRandom;
                $explode          = explode('.', $file_name);
                if ( customCompute($explode) >= 2 ) {
                    if ( preg_match('/\s/', $file_name) ) {
                        $file_name = str_replace(' ', '_', $file_name);
                    }
                    $new_file                = $file_name_rename . '.' . end($explode);
                    $config['upload_path']   = "./uploads/attach";
                    $config['allowed_types'] = "gif|jpg|png|pdf|doc|csv|docx|xlsx|xl";
                    $config['file_name']     = $new_file;
                    $image_info = getimagesize($_FILES['attachment']['tmp_name']);
				    $image_width = $image_info[0];
				    $image_height = $image_info[1];
                    // $config['max_size']      = '5120';
                    // $config['max_width']     = '3000';
                    // $config['max_height']    = '3000';
                    $this->load->library('upload', $config);
                    if ( !$this->upload->do_upload("attachment") ) {
                        $this->form_validation->set_message("fileUpload", $this->upload->display_errors());
                        return false;
                    } else {
                        $fileData = $this->upload->data();

                        resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);
    
                        $this->upload_data['file'] = $this->upload->data();
                        return true;
                    }
                } else {
                    $this->form_validation->set_message("fileUpload", "Invalid file");
                    return false;
                }
            } else {
                $this->upload_data['file'] = [ 'attach' => NULL, 'attach_file_name' => NULL ];
                return true;
            }
        }

        public function open()
        {
            $conversationID = htmlentities(escapeString($this->uri->segment(3)));
            $messageID      = htmlentities(escapeString($this->uri->segment(4)));

            if ( (int) $conversationID && (int) $messageID ) {
                $conversationUser = $this->conversation_m->user_check($conversationID,
                    $this->session->userdata('loginuserID'), $this->session->userdata('usertypeID'));
                if ( customCompute($conversationUser) && $conversationUser->trash != 2) {
                    $conversation = $this->conversation_m->get_single_conversation_msg([ 'msg_id' => $messageID ]);
                    if ( customCompute($conversation) ) {
                        $file = realpath('uploads/attach/' . $conversation->attach_file_name);
                        if ( file_exists($file) ) {
                            $expFileName  = explode('.', $file);
                            $originalname = ( $conversation->attach ) . '.' . end($expFileName);
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($file));
                            readfile($file);
                            exit;
                        } else {
                            redirect(base_url('conversation/index/' . $conversationID));
                        }
                    } else {
                        redirect(base_url('conversation/index/' . $conversationID));
                    }
                } else {
                    redirect(base_url('conversation/index/' . $conversationID));
                }
            } else {
                redirect(base_url('conversation/index'));
            }
        }

        private function _conversation($conversations = [], $message = true, $binds = [])
        {
            $this->data['methodpass']    = htmlentities(escapeString($this->uri->segment(2)));
            if ( customCompute($conversations) ) {
                foreach ( $conversations as $conversationKey => $conversation ) {
                    $user = $this->user_m->get_user_info($conversation->usertypeID, $conversation->user_id);
                    if(customCompute($user)) {
                        $pushItem = ['sender' => $user->name];
                        if($message) {
                            $messages = $this->conversation_m->get_conversation_msg_by_id($conversation->conversation_id);
                            $pushItem['msgCount'] = customCompute($messages);
                        }

                        if(customCompute($binds)) {
                            foreach ($binds as $bindKey => $bind) {
                                $pushItem[$bindKey] = $user->$bind;
                            }
                        }
                        $conversations[ $conversationKey ] = (object) array_merge((array) $conversation, $pushItem);
                    }
                }
            }
            return $conversations;
        }

        private function _currentUser( $userGroupID )
        {
            if ( $userGroupID == 1 ) {
                if ( !$this->input->post('systemadminID') ) {
                    $users = $this->systemadmin_m->get_systemadmin();
                } else {
                    $systemadminID = $this->input->post('systemadminID');
                    $users         = $this->systemadmin_m->get_order_by_systemadmin([ 'systemadminID' => $systemadminID ]);
                }
            } elseif ( $userGroupID == 2 ) {
                if ( !$this->input->post('teacherID') ) {
                    $users = $this->teacher_m->get_teacher();
                } else {
                    $teacherID = $this->input->post('teacherID');
                    $users     = $this->teacher_m->get_order_by_teacher([ 'teacherID' => $teacherID ]);
                }
            } elseif ( $userGroupID == 3 ) {
                $schoolYearID = $this->session->userdata('defaultschoolyearID');
                if ( !$this->input->post('classID') ) {
                    $users = $this->studentrelation_m->get_order_by_student([ 'srschoolyearID' => $schoolYearID ]);
                } else {
                    $classID   = $this->input->post('classID');
                    $studentID = $this->input->post('studentID');
                    if ( $studentID > 0 ) {
                        $users = $this->studentrelation_m->get_order_by_student([
                            'srstudentID'    => $studentID,
                            'srschoolyearID' => $schoolYearID
                        ]);
                    } else {
                        $users = $this->studentrelation_m->get_order_by_student([
                            'srclassesID'    => $classID,
                            'srschoolyearID' => $schoolYearID
                        ]);
                    }
                }
            } elseif ( $userGroupID == 4 ) {
                if ( !$this->input->post('parentsID') ) {
                    $users = $this->parents_m->get_parents();
                } else {
                    $parentsID = $this->input->post('parentsID');
                    $users     = $this->parents_m->get_order_by_parents([ 'parentsID' => $parentsID ]);
                }
            } else {
                if ( !$this->input->post('userID') ) {
                    $users = $this->user_m->get_order_by_user([ 'usertypeID' => $userGroupID ]);
                } else {
                    $userID = $this->input->post('userID');
                    $users  = $this->user_m->get_order_by_user([ 'userID' => $userID ]);
                }
            }
            return $users;
        }

        private function _messageCreate( $userTypeID, $users = [], $conversationID = 0, $message = [] )
        {
            if ( customCompute($users) ) {
                $userType = [
                    1 => 'systemadminID',
                    2 => 'teacherID',
                    3 => 'studentID',
                    4 => 'parentsID',
                    5 => 'userID',
                ];

                $conversationTeacher = [];
                foreach ( $users as $user ) {
                    $userID                = ( isset($userType[ $userTypeID ]) ? $userType[ $userTypeID ] : $userType[5] );
                    $conversationTeacher[] = [
                        'conversation_id' => $conversationID,
                        "user_id"         => $user->$userID,
                        "usertypeID"      => $user->usertypeID,
                        'is_sender'       => 0
                    ];
                }

                $message['conversation_id'] = $conversationID;
                $this->conversation_m->batch_insert_conversation_user($conversationTeacher);
                $messageID = $this->conversation_m->insert_conversation_msg($message);

                // if ( $messageID > 0 ) {
                //     $this->alert_m->insert_alert([
                //         'itemID'     => $messageID,
                //         "userID"     => $this->session->userdata("loginuserID"),
                //         'usertypeID' => $this->session->userdata('usertypeID'),
                //         'itemname'   => 'message'
                //     ]);
                // }
            }
        }

        private function _messageCreatefor( $conversationID = 0, $message = [],$postUsers = [] )
        {
            $users = $postUsers;

            if ( customCompute($users) ) {
                $userType = [
                    1 => 'systemadminID',
                    2 => 'teacherID',
                    3 => 'studentID',
                    4 => 'parentsID',
                    5 => 'userID',
                ];
                
                $conversationTeacher = [];
        		$all_users = [];
                foreach ( $users as $user ) {

                    //$uarray = explode('/',$user);
                    $uarray[0] = substr($user, 0, -1);
					$uarray[1] = substr($user, -1);

                    if($this->session->userdata("loginuserID") != $uarray[0] || $this->session->userdata("usertypeID") != $uarray[1])
                    { 
                        $all_users[] = [
                                'ID' => $uarray[0],
                                'usertypeID' => $uarray[1]
                            ];
                    }    

                    $userID                = ( isset($userType[ $uarray[1] ]) ? $userType[ $uarray[1] ] : $userType[5] );
                    $conversationTeacher[] = [
                        'conversation_id' => $conversationID,
                        "user_id"         => $uarray[0],
                        "usertypeID"      => $uarray[1],
                        'is_sender'       => 0
                    ];
                    // $push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $uarray[0], 'create_usertypeID' => $uarray[1]]), 'fcm_token');
                    // if($push_users) {
                    //     $registered_ids = array_merge($registered_ids, $push_users);
                    // }
                    // $push_message['data'] = [
                    //     'message' => $message['msg'],
                    //     'title' => $message['subject'],
                    //     'action' => 'message',
                    //     'id' => $conversationID
                    // ];
                } 

                $msg = count($all_users) == 1?$message['msg']:$this->session->userdata('name').' : '.$message['msg'];


                $array = [
                    'subject' => $message['subject'],
                    'msg' => $msg,
                    'users' => $all_users,
                    'conversationID' => $conversationID
                ];

                $this->addToCronJob($array);
                // chunk_push_notification($registered_ids, $push_message);
                $message['conversation_id'] = $conversationID;
                $this->conversation_m->batch_insert_conversation_user($conversationTeacher);
                $messageID = $this->conversation_m->insert_conversation_msg($message);

                if ( $messageID > 0 ) {
                    $this->alert_m->insert_alert([
                        'itemID'     => $messageID,
                        "userID"     => $this->session->userdata("loginuserID"),
                        'usertypeID' => $this->session->userdata('usertypeID'),
                        'itemname'   => 'message'
                    ]);
                }
            }
        }

        private function _alertPost( $conversationID = 0 )
        {
            $pluckMessage = pluck($this->alert_m->get_order_by_alert([
                "userID"     => $this->session->userdata("loginuserID"),
                'usertypeID' => $this->session->userdata('usertypeID'),
                'itemname'   => 'message'
            ]), 'itemname', 'itemID');

            $messages = $this->conversation_m->get_conversation_msg_by_id($conversationID);
            if ( customCompute($messages) ) {
                foreach ( $messages as $message ) {
                    if ( !isset($pluckMessage[ $message->msg_id ]) ) {
                        $this->alert_m->insert_alert([
                            'itemID'     => $message->msg_id,
                            "userID"     => $this->session->userdata("loginuserID"),
                            'usertypeID' => $this->session->userdata('usertypeID'),
                            'itemname'   => 'message'
                        ]);
                    }
                }
            }
        }

        public function getAlldatas(){
            $students = $this->student_m->get_allstudents();
            $parents = $this->student_m->get_allstudentsparents();
            $teachers = $this->student_m->get_teachers();
            $admin = $this->student_m->get_admin();
           
            $new = array_merge($students,$parents,$teachers,$admin);
            echo json_encode($new);
        }

        function addToCronJob($array)
        {
            $this->mobile_job_m->insert_job([
                'name' => 'sendConversationMsg',
                'payload' => json_encode([
                    'title' => $array['subject'],
                    'message' => $array['msg'],
                    'users' => $array['users'],
                    'conversationID' => $array['conversationID']
                ]),
            ]);
        }


    }
