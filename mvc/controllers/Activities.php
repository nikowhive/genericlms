<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activities extends Admin_Controller
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
| WEBSITE:			http://iNilabs.net
| -----------------------------------------------------
*/
    function __construct() 
    {
        parent::__construct();
        $this->load->model("feed_m");
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
        $this->load->model("alert_m");
        $this->load->model("student_m");
        $this->load->model("classes_m");
        $this->load->model("activities_m");
        $this->load->model("activitiesmedia_m");
        $this->load->model("activitiesstudent_m");
        $this->load->model("activitiescomment_m");
        $this->load->model("activitiescategory_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('activities', $language);
        $this->load->helper('date');
    }

    public function index()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $activityCategoryID = isset($_GET['category']) ? $_GET['category'] : '';

        $this->data['activityCategoryID'] = $activityCategoryID;
        $this->data['user'] = getAllSelectUser();
        $this->data['userID'] = $this->session->userdata('loginuserID');
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['activitiescategories'] = pluck($this->activitiescategory_m->get_activitiescategory(), 'obj', 'activitiescategoryID');

        if ($activityCategoryID != "" || $activityCategoryID != 0) {
            $activities =  $this->activities_m->getRecentActivities(4, $page, $schoolyearID, $activityCategoryID);
        } else {
            $activities = $this->activities_m->getRecentActivities(4, $page, $schoolyearID);
        }

        $activitiesMedia = [];
        $comments = [];
        foreach ($activities as $key => $activity) {
            $activitiesID = $activity->activitiesID;
            $activity_media = $this->activitiesmedia_m->get_order_by_activitiesmedia(['activitiesID' => $activitiesID]);
            $n_e_media = array();
            foreach ($activity_media as $media) {
                $n_e_media[] = $media->attachment;
            }
            $activities[$key]->media = $n_e_media;

            if (customCompute($activity_media)) {
                $activitiesMedia[$activitiesID] = $activity_media;
            }

            $activity_comments_count = count($this->activitiescomment_m->paginatedActivityComments('', '', ['activitiesID' => $activitiesID]));
            $activities[$key]->comment_count = $activity_comments_count;

            $activity_comments = $this->activitiescomment_m->paginatedActivityComments(5, 0, ['activitiesID' => $activitiesID]);

            if (customCompute($activity_comments)) {
                $reverse = array_reverse($activity_comments);
                $comments[$activitiesID] = $reverse;
            }
        }


        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            if ($_POST) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if ((int)$id) {
                    if ($_POST['comment']) {
                        $array['activitiesID'] = $id;
                        $array['comment'] = $this->input->post('comment');
                        $array['schoolyearID'] = $schoolyearID;
                        $array['userID'] = $this->session->userdata("loginuserID");
                        $array['usertypeID'] = $this->session->userdata("usertypeID");
                        $array['create_date'] = date("Y-m-d H:i:s");
                        $this->activitiescomment_m->insert_activitiescomment($array);
                        $this->session->set_flashdata('success', $this->lang->line("menu_success"));
                        redirect(base_url("activities/index"));
                    }
                }
            }
        }

        $this->data['comments'] = $comments;
        $this->data['activities'] = $activities;
        $this->data['activitiesmedia'] = $activitiesMedia;
        $this->data["subview"] = "activities/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function getMoreActivitiesData()
    {
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $activityCategoryID = isset($_GET['category']) ? $_GET['category'] : '';
        $this->data['activityCategoryID'] = $activityCategoryID;
        $this->data['user'] = getAllSelectUser();
        $this->data['userID'] = $this->session->userdata('loginuserID');
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['activitiescategories'] = pluck($this->activitiescategory_m->get_activitiescategory(), 'obj', 'activitiescategoryID');

        $page = $page * 4;
        if ($activityCategoryID != "" || $activityCategoryID != 0) {
            $activities = $this->activities_m->getRecentActivities(4, $page, $schoolyearID, $activityCategoryID);
        } else {
            $activities = $this->activities_m->getRecentActivities(4, $page, $schoolyearID);
        }

        $activitiesMedia = [];
        foreach ($activities as $key => $activity) {
            $activitiesID = $activity->activitiesID;
            $activity_media = $this->activitiesmedia_m->get_order_by_activitiesmedia(['activitiesID' => $activitiesID]);
            $n_e_media = array();
            foreach ($activity_media as $media) {
                $n_e_media[] = $media->attachment;
            }
            $activities[$key]->media = $n_e_media;

            if (customCompute($activity_media)) {
                $activitiesMedia[$activitiesID] = $activity_media;
            }

            $activity_comments_count = count($this->activitiescomment_m->paginatedActivityComments('', '', ['activitiesID' => $activitiesID]));
            $activities[$key]->comment_count = $activity_comments_count;

            $activity_comments = $this->activitiescomment_m->paginatedActivityComments(5, 0, ['activitiesID' => $activitiesID]);

            if (customCompute($activity_comments)) {
                $reverse = array_reverse($activity_comments);
                $comments[$activitiesID] = $reverse;
            }
        }


        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            if ($_POST) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if ((int)$id) {
                    if ($_POST['comment']) {
                        $array['activitiesID'] = $id;
                        $array['comment'] = $this->input->post('comment');
                        $array['schoolyearID'] = $schoolyearID;
                        $array['userID'] = $this->session->userdata("loginuserID");
                        $array['usertypeID'] = $this->session->userdata("usertypeID");
                        $array['create_date'] = date("Y-m-d H:i:s");
                        $this->activitiescomment_m->insert_activitiescomment($array);
                        $this->session->set_flashdata('success', $this->lang->line("menu_success"));
                        redirect(base_url("activities/index"));
                    }
                }
            }
        }

        $this->data['comments'] = $comments;
        $this->data['activities'] = $activities;
        $this->data['activitiesmedia'] = $activitiesMedia;

        if ($this->data['activities']) {
            echo $this->load->view('activities/autoload_activities', $this->data, true);
            exit;
        } else {
            showBadRequest(null, "No data.");
        }
    }

    public function media()
    {
        $media_id = $this->input->get('media_id');
        $this->data['activitiesmedia'] = pluck_multi_array($this->activitiesmedia_m->get_order_by_activitiesmedia(['activitiesID' => $media_id]), 'obj', 'activitiesID');
        echo json_encode($this->data['activitiesmedia']);
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
                'field' => 'attachment1[]',
                'label' => $this->lang->line("attachment"),
                'rules' => 'trim|xss_clean|callback_multiplephotoupload'
            )
        );
        return $rules;
    }

    public function multiplephotoupload()
    {
        if ($_FILES) {
            if ($_FILES['attachment']['name'][0] !== "") {
                if (empty(array_filter($_POST['caption']))) {
                    $this->form_validation->set_message("multiplephotoupload", 'The %s caption field is required.');
                    return FALSE;
                }

                $filesCount = customCompute($_FILES['attachment']['name']);
                $uploadData = array();
                $uploadPath = 'uploads/activities';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                for ($i = 0; $i < $filesCount; $i++) {
                    $_FILES['attach']['name'] = $_FILES['attachment']['name'][$i];
                    $_FILES['attach']['type'] = $_FILES['attachment']['type'][$i];
                    $_FILES['attach']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                    $_FILES['attach']['error'] = $_FILES['attachment']['error'][$i];
                    $_FILES['attach']['size'] = $_FILES['attachment']['size'][$i];

                    $config['upload_path']   = "./uploads/activities";
                    $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";


                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('attach')) {
                        $fileData = $this->upload->data();
                        if($fileData['is_image'] == '1')
                        {
                            $image_width = $fileData['image_width'];
					        $image_height = $fileData['image_height'];
                            
                            resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height); 
                        }
                        $uploadData[$i]['attachment'] = $fileData['file_name'];
                        $uploadData[$i]['caption'] = $_POST['caption'][$i];
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

    public function add()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datepicker/datepicker.css',
                    'assets/timepicker/timepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/tooltipster/css/tooltipster.bundle.min.css'
                ),
                'js' => array(
                    'assets/datepicker/datepicker.js',
                    'assets/select2/select2.js',
                    'assets/tooltipster/js/tooltipster.bundle.min.js',
                    'assets/timepicker/timepicker.js'
                )
            );
            $categoryID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$categoryID) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['activities_categories'] = $this->activitiescategory_m->get_activitiescategory();
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "activities/add";
                        $this->load->view('_layout_main', $this->data);
                    } else {
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

                        $id = $this->activities_m->insert_activities($array);

                        if ($id) {
                            $photos = $this->upload_data['files'];
                            if (customCompute($photos)) {
                                foreach ($photos as $key => $photo) {
                                    $photos[$key]['activitiesID'] = $id;
                                }
                                $this->activitiesmedia_m->insert_batch_activitiesmedia($photos);
                            }

                            $this->feed_m->insert_feed(
                                array(
                                    'itemID' => $id,
                                    'userID' => $this->session->userdata("loginuserID"),
                                    'usertypeID' => $this->session->userdata('usertypeID'),
                                    'itemname' => 'activity',
                                    'schoolyearID' => $this->session->userdata('defaultschoolyearID'),
                                    'published' => 1,
                                    'published_date' => date('Y-m-d'),
                                    'status' => 'public'
                                )
                            );
                        }

                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("activities/index"));
                    }
                } else {
                    $this->data["subview"] = "activities/add";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "activities/add";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/timepicker/timepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/tooltipster/css/tooltipster.bundle.min.css'
            ),
            'js' => array(
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js',
                'assets/tooltipster/js/tooltipster.bundle.min.js',
                'assets/timepicker/timepicker.js'
            )
        );

        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $this->data['activity'] = $this->activities_m->get_activities($id);
            $this->data['activities_category'] = $this->activitiescategory_m->get_single_activitiescategory(array("activitiescategoryID" => $this->data['activity']->activitiescategoryID));
            $this->data['activities_media'] = $this->activitiesmedia_m->get_order_by_activitiesmedia(['activitiesID' => $id]);

            if ($_POST) {
                  $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "activities/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                $array = array(
                    "title"       => $this->input->post("title"),
                    "description" => $this->input->post("description"),
                    "usertypeID"  => $this->session->userdata('usertypeID'),
                    "userID"      => $this->session->userdata('loginuserID'),
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
                $photos = $this->upload_data['files'];
                if (customCompute($photos)) {
                    foreach ($photos as $key => $photo) {
                        $photos[$key]['activitiesID'] = $id;
                    }

                    $this->activitiesmedia_m->insert_batch_activitiesmedia($photos);
                }
                
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("activities/index"));
                }
            } else {
                $this->data["subview"] = "activities/edit";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }



    public function deleteImage()
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            // $imgData = $this->activitiesmedia_m->get_single_activitiesmedia(["activitiesmediaID" => $id]);
            // @unlink('uploads/activities/' . $imgData['attachment']);
            $delete = $this->activitiesmedia_m->delete_activitiesmedia($id);
            $retArray['status'] = true;
            $retArray['message'] = $this->lang->line('menu_success');
            echo json_encode($retArray);
            exit;
        }
    }

    public function comment()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ($_POST) {
            $array['activitiesID'] = $this->input->post('activity_id');
            $array['comment'] = $this->input->post('comment');
            $array['schoolyearID'] = $schoolyearID;
            $array['userID'] = $this->session->userdata("loginuserID");
            $array['usertypeID'] = $this->session->userdata("usertypeID");
            $array['create_date'] = date("Y-m-d H:i:s");
            $data = $this->activitiescomment_m->insert_activitiescomment($array);
            if ($data) {
                $this->pushNotificationOfComment($array);
            }
            echo $data;
        }
    }

    public function delete()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $usertypeID = $this->session->userdata('usertypeID');
            $userID = $this->session->userdata('loginuserID');

            if ((int)$id) {
                $activities = $this->activities_m->get_activities($id);
                if (($usertypeID == $activities->usertypeID && $userID == $activities->userID) || ($usertypeID == 1)) {
                    $this->activities_m->delete_activities($id);
                    $feed = $this->feed_m->get_single_feed(array('itemID' => $id, 'itemname' => 'activity'));
                    if ($feed) {
                        $this->feed_m->delete_feed($feed->feedID);
                    }
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                }

                redirect(base_url("activities/index"));
            } else {
                redirect(base_url("activities/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete_comment()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {

            $id = $this->input->post('id');
            $usertypeID = $this->session->userdata('usertypeID');
            $userID = $this->session->userdata('loginuserID');

            if ((int)$id) {
                $comment = $this->activitiescomment_m->get_activitiescomment($id);
                $activities = $this->activities_m->get_activities($comment->activitiesID);
                if (($usertypeID == $activities->usertypeID && $userID == $activities->userID) || ($usertypeID == 1)) {
                    $this->activitiescomment_m->delete_activitiescomment($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                }

                $retArray['status'] = TRUE;;
                $retArray['message'] = $this->lang->line('menu_success');
                echo json_encode($retArray);
                exit;
            } else {
                redirect(base_url("activities/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
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
        $all_users = array_merge($teachers, $students, $parents, $systemadmins, $users);

        $newUsers = [];
        foreach ($all_users as $all_user) {
            $newUsers[] = $all_user['ID'] . $all_user['usertypeID'];
        }
        $all_users = $newUsers;

        // post author
        $postAuthor = $activitiesObj->userID . $activitiesObj->usertypeID;
        if ($postAuthor != $array['userID'] . $array['usertypeID']) {
            array_push($all_users, $postAuthor);
        }

        $sall_users = serialize($all_users);

        $this->job_m->insert_job([
            'name' => 'sendComment',
            'payload' => json_encode([
                'title' => "Comment on " . $activitiesObj->title,  // title is necessary
                'users' => $sall_users,
            ]),
        ]);

        $this->mobile_job_m->insert_job([
            'name' => 'sendComment',
            'payload' => json_encode([
                'title' => "Comment on " . $activitiesObj->title,  // title is necessary
                'users' => $sall_users,
                'message' => $array['comment']
            ]),
        ]);
    }

    public function getComment()
    {

        $commentID = $this->input->get('commentID');
        $activitiesID = $this->input->get('activitiesID');

        $activities_comment = $this->activitiescomment_m->get_single_activitiescomment(['activitiescommentID' => $commentID, 'activitiesID' => $activitiesID]);

        if ($activities_comment) {
            $this->data['comment'] = $activities_comment->comment;
            $this->data['commentID'] = $commentID;
            echo $this->load->view('activities/comment_template', $this->data, true);
        } else {
            $this->data['comment'] = '';
            $this->data['commentID'] = '';
            echo $this->load->view('activities/comment_template', $this->data, true);
        }

        exit;
    }

    public function editComment()
    {

        $array['comment']      = $this->input->post('comment');
        $commentID      = $this->input->post('commentID');

        $data = $this->activitiescomment_m->update_activitiescomment($array, $commentID);
        if ($data) {
            echo $array['comment'];
        } else {
            echo false;
        }
    }

    public function getMoreActivityCommentData()
    {
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $activitiesID = $this->input->get('activitiesID');
        $activities_comments = $this->activitiescomment_m->paginatedActivityComments(5, $page, ['activitiesID' => $activitiesID]);
        $reverse = array_reverse($activities_comments);
        $this->data['comments'] = $reverse;
        if ($activities_comments) {
            echo $this->load->view('activities/autoload_activities_comment', $this->data, true);
            exit;
        } else {
            showBadRequest(null, "No data.");
        }
    }
   
}
