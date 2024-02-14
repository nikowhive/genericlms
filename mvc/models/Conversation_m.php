<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class conversation_m extends MY_Model
    {
        protected $_table_name = 'conversation_message_info';
        protected $_primary_key = 'id';
        protected $_primary_filter = 'intval';
        protected $_order_by = "id ASC";

        public function __construct()
        {
            parent::__construct();
            $this->load->model("fcmtoken_m");
        }

        public function get_conversation( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_my_conversations_for_api()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('conversation_msg.conversation_id');
            $this->db->from('conversation_msg');
            $this->db->join('conversation_message_info',
                'conversation_msg.conversation_id=conversation_message_info.id', 'left');
            $this->db->where('conversation_msg.user_id', $userID);
            $this->db->where('conversation_msg.usertypeID', $usertypeID);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.modify_date', 'desc');
            $this->db->limit(5);
            $query = $this->db->get();
            return $query->result();
        }

        public function get_my_conversations()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->distinct();
            $this->db->select('conversation_user.*,conversation_user.status as read_status, conversation_msg.*, conversation_message_info.*,
                COALESCE(systemadmin.name, teacher.name, student.name, parents.name, user.name) as name,
                COALESCE(systemadmin.photo, teacher.photo, student.photo, parents.photo, user.photo) as photo
            ');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->join('systemadmin', 'conversation_msg.user_id=systemadmin.systemadminID AND conversation_msg.usertypeID = 1', 'left');
            $this->db->join('teacher', 'conversation_msg.user_id=teacher.teacherID AND conversation_msg.usertypeID = 2', 'left');
            $this->db->join('student', 'conversation_msg.user_id=student.studentID AND conversation_msg.usertypeID = 3', 'left');
            $this->db->join('parents', 'conversation_msg.user_id=parents.parentsID AND conversation_msg.usertypeID = 4', 'left');
            $this->db->join('user', 'conversation_msg.user_id=user.userID AND conversation_msg.usertypeID = 5', 'left');

            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 0);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.modify_date', 'desc');
            $this->db->group_by('conversation_message_info.id');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_my_conversations_draft()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('*');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 0);
            $this->db->where('conversation_user.is_sender', 1);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 1);
            $this->db->order_by('conversation_message_info.id', 'desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_my_conversations_sent()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('*');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 0);
            $this->db->where('conversation_user.is_sender', 1);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.id', 'desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_my_conversations_trash()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('*');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 1);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.id', 'desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_single_conversation_msg( $array )
        {
            $this->db->from('conversation_msg');
            $this->db->where($array);
            $query = $this->db->get();
            return $query->row();
        }

        public function get_conversation_msg_by_id( $conversationID = 0 )
        {
            $this->db->order_by("msg_id", "asc");
            $query = $this->db->get_where('conversation_msg', [ 'conversation_id' => $conversationID,'deleted' => 0 ]);
            return $query->result();
        }

        public function get_conversation_users_by_id( $conversationID )
        {
            $query = $this->db->get_where('conversation_user', [ 'conversation_id' => $conversationID ]);
            return $query->result();
        }

        public function get_latest_users_by_id( $conversationID )
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->distinct();
            $this->db->select('conversation_user.*,conversation_msg.msg,
                COALESCE(systemadmin.name, teacher.name, student.name, parents.name, user.name) as name,
                COALESCE(systemadmin.photo, teacher.photo, student.photo, parents.photo, user.photo) as photo
            ');
            $this->db->from('conversation_user');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->join('systemadmin', 'conversation_user.user_id=systemadmin.systemadminID AND conversation_user.usertypeID = 1', 'left');
            $this->db->join('teacher', 'conversation_user.user_id=teacher.teacherID AND conversation_user.usertypeID = 2', 'left');
            $this->db->join('student', 'conversation_user.user_id=student.studentID AND conversation_user.usertypeID = 3', 'left');
            $this->db->join('parents', 'conversation_user.user_id=parents.parentsID AND conversation_user.usertypeID = 4', 'left');
            $this->db->join('user', 'conversation_user.user_id=user.userID AND conversation_user.usertypeID = 5', 'left');
            $this->db->limit(5);
            $this->db->where('conversation_msg.conversation_id', $conversationID);
            $this->db->where("(conversation_user.user_id != '$userID' OR conversation_user.usertypeID != '$usertypeID')");
            $this->db->order_by('conversation_msg.create_date desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_all_users_by_id( $conversationID, $page )
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->distinct();
            $this->db->select('conversation_user.*,
                COALESCE(systemadmin.name, teacher.name, student.name, parents.name, user.name) as name,
                COALESCE(systemadmin.photo, teacher.photo, student.photo, parents.photo, user.photo) as photo
            ');
            $this->db->from('conversation_user');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->join('systemadmin', 'conversation_user.user_id=systemadmin.systemadminID AND conversation_user.usertypeID = 1', 'left');
            $this->db->join('teacher', 'conversation_user.user_id=teacher.teacherID AND conversation_user.usertypeID = 2', 'left');
            $this->db->join('student', 'conversation_user.user_id=student.studentID AND conversation_user.usertypeID = 3', 'left');
            $this->db->join('parents', 'conversation_user.user_id=parents.parentsID AND conversation_user.usertypeID = 4', 'left');
            $this->db->join('user', 'conversation_user.user_id=user.userID AND conversation_user.usertypeID = 5', 'left');
            $this->db->limit(20, $page);
            $this->db->where('conversation_msg.conversation_id', $conversationID);
            $this->db->order_by('conversation_msg.create_date desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function insert_conversation( $array )
        {
            $insetID = parent::insert($array);
            return $insetID;
        }

        public function insert_conversation_user( $array )
        {
            $this->db->insert("conversation_user", $array);
            return true;
        }

        public function batch_insert_conversation_user( $array )
        {
            $this->db->insert_batch('conversation_user', $array);
            $id = $this->db->insert_id();
            return $id;
        }

        public function insert_conversation_msg( $array )
        {
            $this->db->insert("conversation_msg", $array);
            $id = $this->db->insert_id();
            return $id;
        }

        public function update_conversation( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function soft_delete_conversation_msg( $data, $id = null )
        {
            $this->db->where('msg_id',$id);
            $this->db->update('conversation_msg', $data);
            return $id;
        }

        public function edit_conversation_msg( $data, $id = null )
        {
            $this->db->where('msg_id',$id);
            $this->db->update('conversation_msg', $data);
            return $id;
        }

        public function delete_conversation( $id )
        {
            parent::delete($id);
            return true;
        }

        public function user_check( $conv_id, $user_id, $usertypeID )
        {
            $query = $this->db->get_where('conversation_user',
                [ 'conversation_id' => $conv_id, 'user_id' => $user_id, 'usertypeID' => $usertypeID ]);
            return $query->row();
        }

        public function trash_conversation( $data, $id )
        {
            $usertypeID = $this->session->userdata("usertypeID");
            $userID     = $this->session->userdata("loginuserID");
            $query      = $this->db->get_where('conversation_user',
                [ 'conversation_id' => $id, 'user_id' => $userID, 'usertypeID' => $usertypeID ]);
            if ( customCompute($query->row()) == 1 ) {
                $this->db->where('conversation_id', $id);
                $this->db->where('user_id', $userID);
                $this->db->where('usertypeID', $usertypeID);
                $this->db->update('conversation_user', $data);
            }
            return true;
        }

        public function get_usertype_by_permission()
        {
            $this->db->select('*');
            $this->db->from('permission_relationships');
            $this->db->join('permissions', 'permissions.permissionID = permission_relationships.permission_id', 'LEFT');
            $this->db->join('usertype', 'usertype.usertypeID = permission_relationships.usertype_id', 'LEFT');
            $this->db->where([ 'permissions.name' => 'conversation' ]);
            $query = $this->db->get();
            return $query->result();
        }

        public function parentconversation($parents,$conversationMessage,$conversationID){
            $conversationTeacher = [];
            $registered_ids = [];
            $message = $conversationMessage;
            foreach ( $parents as $parent ) {
                //echo $parent;
                //$uarray = explode('/',$user);

                //$userID                = ( isset($userType[ $uarray[1] ]) ? $userType[ $uarray[1] ] : $userType[5] );
                $conversationTeacher[] = [
                    'conversation_id' => $conversationID,
                    "user_id"         => $parent,
                    "usertypeID"      => 4,
                    'is_sender'       => 0
                ];
                $push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $parent, 'create_usertypeID' => 4]), 'fcm_token');
                if($push_users) {
                    $registered_ids = array_merge($registered_ids, $push_users);

                }
                $push_message['data'] = [
                    'message' => $message['msg'],
                    'title' => $message['subject'],
                    'action' => 'message'
                ];
            } 
            sendNotification($registered_ids, $push_message);
            $message['conversation_id'] = $conversationID;
            $this->batch_insert_conversation_user($conversationTeacher);
            $messageID = $this->insert_conversation_msg($message);

            if ( $messageID > 0 ) {
                $this->alert_m->insert_alert([
                    'itemID'     => $messageID,
                    "userID"     => $this->session->userdata("loginuserID"),
                    'usertypeID' => $this->session->userdata('usertypeID'),
                    'itemname'   => 'message'
                ]);
            }
        }

        public function studentconversation($students,$conversationMessage,$conversationID){
        
            $conversationTeacher = [];
            $registered_ids = [];
            $message = $conversationMessage;
            foreach ( $students as $student ) {
                //echo $parent;
                //$uarray = explode('/',$user);

                //$userID                = ( isset($userType[ $uarray[1] ]) ? $userType[ $uarray[1] ] : $userType[5] );
                $conversationTeacher[] = [
                    'conversation_id' => $conversationID,
                    "user_id"         => $student,
                    "usertypeID"      => 3,
                    'is_sender'       => 0
                ];
                $push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $student, 'create_usertypeID' => 3]), 'fcm_token');
                if($push_users) {
                    $registered_ids = array_merge($registered_ids, $push_users);

                }
                $push_message['data'] = [
                    'message' => $message['msg'],
                    'title' => $message['subject'],
                    'action' => 'message'
                ];
            } 
            sendNotification($registered_ids, $push_message);
            $message['conversation_id'] = $conversationID;
            $this->batch_insert_conversation_user($conversationTeacher);
            $messageID = $this->insert_conversation_msg($message);

            if ( $messageID > 0 ) {
                $this->alert_m->insert_alert([
                    'itemID'     => $messageID,
                    "userID"     => $this->session->userdata("loginuserID"),
                    'usertypeID' => $this->session->userdata('usertypeID'),
                    'itemname'   => 'message'
                ]);
            }
        }
      
 
        public function get_conversation_users_by_conversation_id($ids)
        {
           
            $this->db->select('conversation_user.user_id as id,conversation_user.usertypeID as usertypeID,
            COALESCE(systemadmin.name, teacher.name, student.name, parents.name, user.name) as name,
            COALESCE(systemadmin.photo, teacher.photo, student.photo, parents.photo, user.photo) as photo,
            COALESCE(systemadmin.email, teacher.email, student.email, parents.email, user.email) as email,
            COALESCE(systemadmin.address, teacher.address, student.address, parents.address, user.address) as address
             ');
            $this->db->from('conversation_user');
            $this->db->join('systemadmin', 'conversation_user.user_id=systemadmin.systemadminID AND conversation_user.usertypeID = 1', 'left');
            $this->db->join('teacher', 'conversation_user.user_id=teacher.teacherID AND conversation_user.usertypeID = 2', 'left');
            $this->db->join('student', 'conversation_user.user_id=student.studentID AND conversation_user.usertypeID = 3', 'left');
            $this->db->join('parents', 'conversation_user.user_id=parents.parentsID AND conversation_user.usertypeID = 4', 'left');
            $this->db->join('user', 'conversation_user.user_id=user.userID AND conversation_user.usertypeID = 5', 'left');
            $this->db->where_in('conversation_id',$ids);
            $this->db->where('is_sender',0);
            $this->db->where('trash',0);
            $this->db->limit(15);
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_my_assign_conversations_for_api()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('conversation_msg.conversation_id');
            $this->db->from('conversation_msg');
            $this->db->join('conversation_message_info',
                'conversation_msg.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_user',
            'conversation_msg.conversation_id=conversation_user.conversation_id', 'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.is_sender',0);
            $this->db->where('conversation_user.trash',0);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.modify_date', 'desc');
            $this->db->limit(50);
            $query = $this->db->get();
            return $query->result();
        }

        public function update_conversation_user($conversationID, $userID, $userTypeID){
            $array = [
               'status' => 'read'
            ];
            $this->db->where('conversation_id',$conversationID);
            $this->db->where('user_id',$userID);
            $this->db->where('usertypeID',$userTypeID);
            $this->db->update('conversation_user', $array);
            return true;
        }
   
    }