<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Holiday extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("alert_m");
        $this->load->model("feed_m");
        $this->load->model("student_m");
        $this->load->model('parents_m');
        $this->load->model('teacher_m');
        $this->load->model("holiday_m");
        $this->load->model("fcmtoken_m");
        $this->load->model('systemadmin_m');
        $this->load->model("holiday_media_m");
        $this->load->model("holiday_comment_m");
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
    }

    public function index_get($page=1)
    {
        if (permissionChecker('holiday_view')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $this->holiday_m->order('published_date DESC');

            $allusers     = getAllSelectUser();

            $this->data['userType'] = $this->session->userdata('usertypeID');

            $page  = 20 * ($page - 1);

            $isAdmin = $this->session->userdata('usertypeID') == 1?true:false;
	     	$dbHolidays = $this->holiday_m->getRecentHolidays(20, $page, $schoolyearID, '', '',$isAdmin);
            
            foreach($dbHolidays as $key => $holiday )
             {
                 $holidayID = $holiday->holidayID;
                 $holiday_medias = pluck($this->holiday_media_m->get_order_by_holiday_media(['holidayID' => $holidayID]),'obj');
                 $dbHolidays[$key]->media = $holiday_medias;

                 $comments = pluck($this->holiday_comment_m->get_order_by_holiday_comment(['holidayID' => $holidayID]),'obj');
                 if(customCompute($comments)){
					foreach($comments as $k=>$comment){
						$comments[$k]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$comments[$k]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
                    $dbHolidays[$key]->comments = $comments;
                }else{
                    $dbHolidays[$key]->comments  = [];
                }
                 

             }
           
             $this->retdata['holidays'] = $dbHolidays;

            $this->response([
                'status' => true,
                'message' => 'Success',
                'data' => $this->retdata['holidays'],
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not allowed',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function view_get($id = null)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ((int) $id) {
            $this->retdata['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
            if (customCompute($this->retdata['holiday'])) {
               
                $array = array(
                    "itemID" => $id,
                    "userID" => $this->session->userdata("loginuserID"),
                    "usertypeID" => $this->session->userdata("usertypeID"),
                    "itemname" => 'holiday',
                );
                $alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'holiday'));
                if (!customCompute($alert)) {
                    $this->alert_m->insert_alert($array);
                }

                $feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'holiday'));
                if (!customCompute($feed)) {
                    $array['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
				    $array['published'] = $this->data['holiday']->published;
                    $array['published_date'] = $this->data['holiday']->published_date;
					$array['status'] = 'public';
                    $this->feed_m->insert_feed($array);
                }

                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $this->retdata,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("holiday_title"),
                'rules' => 'trim|required|xss_clean|max_length[75]|min_length[3]',
            ),
            array(
                'field' => 'fdate',
                'label' => $this->lang->line("holiday_fdate"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid',
            ),
            array(
                'field' => 'tdate',
                'label' => $this->lang->line("holiday_tdate"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_todate_valid',
            ),
            array(
				'field' => 'published_date',
				'label' => $this->lang->line("holiday_"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
			),
            array(
                'field' => 'holiday_details',
                'label' => $this->lang->line("holiday_details"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
				'field' => 'photos[]',
				'label' => $this->lang->line("holiday_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
			)
        );
        return $rules;
    }

    public function send_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("holiday_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean',
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("holiday_subject"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("holiday_message"),
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'holidayID',
                'label' => $this->lang->line("holiday_holidayID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data',
            ),
        );
        return $rules;
    }

    public function date_valid($date)
    {
        if (strlen($date) < 10) {
            $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
            return false;
        } else {
            $arr = explode("-", $date);
            $dd = $arr[0];
            $mm = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                return true;
            } else {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return false;
            }
        }
    }

    public function todate_valid($date)
    {
        $fdate = $this->input->post('fdate');
        if (strlen($date) < 10) {
            $this->form_validation->set_message("todate_valid", "%s is not valid dd-mm-yyyy");
            return false;
        } else {
            $arr = explode("-", $date);
            $dd = $arr[0];
            $mm = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                $fdate = strtotime($fdate);
                $date = strtotime($date);
                if ($fdate > $date) {
                    $this->form_validation->set_message("todate_valid", "%s must be greater than From Date");
                    return false;
                } else {
                    return true;
                }
            } else {
                $this->form_validation->set_message("todate_valid", "%s is not valid dd-mm-yyyy");
                return false;
            }
        }
    }

    public function unique_data($data)
    {
        if ($data != '') {
            if ($data == '0') {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return false;
            }
            return true;
        }
        return true;
    }

    public function photoupload()
    {
        $id = htmlentities(escapeString($this->uri->segment(5)));
        $holiday = array();
        if ((int) $id) {
            $holiday = $this->holiday_m->get_holiday($id);
        }

        $new_file = "holiday.png";
        if ($_FILES["photo"]['name'] != "") {
            $file_name = $_FILES["photo"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random . $this->input->post('title') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file = $file_name_rename . '.' . end($explode);
                $config['upload_path'] = "./uploads/holiday";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name'] = $new_file;
                // $config['max_size'] = '5120';
                // $config['max_width'] = '3000';
                // $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
            if (customCompute($holiday)) {
                $this->upload_data['file'] = array('file_name' => $holiday->photo);
                return true;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                return true;
            }
        }
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
				$uploadPath = 'uploads/holiday';
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777, true);
				}

				for ($i = 0; $i < $filesCount; $i++) {
					$_FILES['attach']['name'] = $_FILES['photos']['name'][$i];
					$_FILES['attach']['type'] = $_FILES['photos']['type'][$i];
					$_FILES['attach']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
					$_FILES['attach']['error'] = $_FILES['photos']['error'][$i];
					$_FILES['attach']['size'] = $_FILES['photos']['size'][$i];

					$config['upload_path'] = $uploadPath;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';

					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('attach')) {
						$fileData = $this->upload->data();
                        $image_width = $fileData['image_width'];
					    $image_height = $fileData['image_height'];

                        resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height); 

						$uploadData[$i]['attachment'] = $fileData['file_name'];
						$uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
						$uploadData[$i]['caption'] = $_POST['caption'][$i];
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

    public function create_post()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1 || permissionChecker('holiday_add'))) {

            if ($_POST) {
                $rules = $this->rules();
                
                $this->form_validation->set_rules($rules);

                if ($this->form_validation->run() == false) {
                    $this->response([
                        'status' => false,
                        'message' => $this->form_validation->error_array(),
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
                } else {

                    $currentDate = date('d-m-Y');
					$publishDate = $this->input->post("published_date");

					if ($publishDate <= $currentDate) {
						$published = $array["published"] = 1;
					}else{
                        $published = $array["published"] = 2;
                    }

                    $array['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
                    $array["title"] = $this->input->post("title");
                    $array["fdate"] = date("Y-m-d", strtotime($this->input->post("fdate")));
                    $array["tdate"] = date("Y-m-d", strtotime($this->input->post("tdate")));
                    $array["published_date"] = date("Y-m-d", strtotime($this->input->post("published_date")));
                    $array["details"] = $this->input->post("holiday_details");
                    $array['create_date'] = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_usertypeID'] = $this->session->userdata('usertypeID');

                    $this->holiday_m->insert_holiday($array);
                    $holidayID = $this->db->insert_id();

                    // insert media
					$photos = $this->upload_data['files'];
					if (!empty($holidayID)) {
						if (customCompute($photos)) {
							foreach ($photos as $key => $photo) {
								$photos[$key]['holidayID'] = $holidayID;
							}
							$this->holiday_media_m->insert_batch_holiday_media($photos);
						}
					}

                    if($holidayID){
                        
                    $this->alert_m->insert_alert(array('itemID' => $holidayID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'holiday'));
					$this->feed_m->insert_feed(
                        array(
                            'itemID' => $holidayID,
                            'userID' => $this->session->userdata("loginuserID"),
                            'usertypeID' => $this->session->userdata('usertypeID'),
                            'itemname' => 'holiday',
                            'schoolyearID' => $this->session->userdata('defaultschoolyearID'),
                            'published'  => $published,
							'published_date' => date("Y-m-d", strtotime($this->input->post("published_date"))),
							'status' => 'public'
                        )
                    );

                    $array["id"] = $holidayID;
                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => $array,
                    ], REST_Controller::HTTP_OK);
                }
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No fields values',
                    'data' => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not allowed',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    function sendFcmNotification($data) {
		$registered_ids = pluck($this->fcmtoken_m->get_order_by_fcm_token(), 'fcm_token');
		$message['data'] = [
			'message' => $data['details'],
			'title' => $data['title'],
			'photo' => base_url('/uploads/holiday/'.$data['photo']),
            'action' => 'holiday'
		];
		chunk_push_notification($registered_ids, $message);
	}

    public function update_post()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1 || permissionChecker('holiday_edit'))) {

            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
                if ($this->data['holiday']) {
                    if ($_POST) {
                        $rules = $this->rules();
                        
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == false) {
                            $this->response([
                                'status' => false,
                                'message' => $this->form_validation->error_array(),
                                'data' => [],
                            ], REST_Controller::HTTP_OK);
                        } else {
                            $array = array(
                                "title" => $this->input->post("title"),
                                "details" => $this->input->post("holiday_details"),
                                "fdate" => date("Y-m-d", strtotime($this->input->post("fdate"))),
                                "tdate" => date("Y-m-d", strtotime($this->input->post("tdate"))),
                                "published_date" => date("Y-m-d", strtotime($this->input->post("published_date")))
                            );
                           
                            if($this->holiday_m->update_holiday($array, $id)){
                            // insert media
                            $photos = $this->upload_data['files'];
                            if (customCompute($photos)) {
                                    foreach ($photos as $key => $photo) {
                                        $photos[$key]['holidayID'] = $id;
                                    }
                                    $this->holiday_media_m->insert_batch_holiday_media($photos);
                            }
                            
                            $this->response([
                                'status' => true,
                                'message' => 'Success',
                                'data' => $array,
                            ], REST_Controller::HTTP_OK);
                        }
                        }
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Error',
                            'data' => [],
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Error',
                        'data' => [],
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error',
                    'data' => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not allowed',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function index_delete()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1 || permissionChecker('holiday_delete'))) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
                if (customCompute($this->data['holiday'])) {
                    if (config_item('demo') == false) {
                        if ($this->data['holiday']->photo != 'holiday.png' && $this->data['holiday']->photo != '') {
                            if ($this->data['holiday']->photo != "" && file_exists(FCPATH . 'uploads/holiday/' . $this->data['holiday']->photo)) {
                                unlink(FCPATH . 'uploads/holiday/' . $this->data['holiday']->photo);
                            }
                        }
                    }
                    $feed = $this->feed_m->get_single_feed(['itemID' => $id,'itemname' => 'holiday']);
					$this->holiday_m->delete_holiday($id);
					if($feed){
						$this->feed_m->delete_feed($feed->feedID);
					}
                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => $id,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Error',
                        'data' => [],
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error',
                    'data' => [],
                ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function getLatestDate()
    {
        
        $holiday = $this->holiday_m->getLatestHoliday();

        $dateArray = [];
        
        if ($holiday) {
            $hDate = date('Y-m-d', strtotime($holiday->published_date));
            $dateArray[] = $hDate;
        }

        if (customCompute($dateArray)) {
            $dateArray = $dateArray;
        } else {
            $dateArray = [date('Y-m-d')];
        }

        $latestdate =  max($dateArray);
        $startDate = date('Y-m-d', strtotime("-14 days", strtotime($latestdate)));
        $endDate = $latestdate;


        return [$startDate, $endDate];
    }

    public function comment_add_post($id = '')
    {
        if(!$id){
            $this->response([
                'status' => false,
                'message' => 'Holiday ID is empty',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $holiday = $this->holiday_m->get_single_holiday(['holidayID' => $id]);
        if(!$holiday){
            $this->response([
                'status' => false,
                'message' => 'Holiday not found.',
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
       
            $array['holidayID'] = $id;
            $array['comment'] = $this->input->post('comment');
            $array['schoolyearID'] = $schoolyearID;
            $array['userID'] = $this->session->userdata("loginuserID");
            $array['usertypeID'] = $this->session->userdata("usertypeID");
            $array['create_date'] = date("Y-m-d H:i:s");
            $data = $this->holiday_comment_m->insert_holiday_comment($array);
            $insert_id = $this->db->insert_id();
            $comment = $this->holiday_comment_m->get_holiday_comment($insert_id);
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
        $data = $this->holiday_comment_m->update_holiday_comment($array,$commentID);
        $comment = $this->holiday_comment_m->get_holiday_comment($commentID);
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

				$comment = $this->holiday_comment_m->get_holiday_comment($id);
                if(!$comment){
                    $this->response([
                        'status' => false,
                        'message' => 'Comment not found.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$holiday = $this->holiday_m->get_holiday($comment->holidayID);
				if (($usertypeID == $holiday->create_usertypeID && $userID == $holiday->create_userID) || ($usertypeID == 1)) {
					$this->holiday_comment_m->delete_holiday_comment($id);
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
                    'status' => false,
                    'message' => 'Media ID is empty',
                    'data' => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }

			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

				$media = $this->holiday_media_m->get_holiday_media($id);
                if(is_null($media)){
                    $this->response([
                        'status' => false,
                        'message' => 'Media not found.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$holiday = $this->holiday_m->get_holiday($media->holidayID);
				if (($usertypeID == $holiday->create_usertypeID && $userID == $holiday->create_userID) || ($usertypeID == 1)) {
					$this->holiday_media_m->delete_holiday_media($id);
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

    function pushNotificationOfComment($array)
	{
		$holidayObj = $this->holiday_m->get_single_holiday([
			'holidayID' => $array['holidayID']
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
		$postAuthor = $holidayObj->create_userID.$holidayObj->create_usertypeID;
		if($postAuthor != $array['userID'].$array['usertypeID']){
            array_push($all_users,$postAuthor);
		}
		
		$sall_users = serialize($all_users);
 
		$this->job_m->insert_job([
			'name' => 'sendComment',
			'payload' => json_encode([
				'title' => "Comment on ".$holidayObj->title,  // title is necessary
				'users' => $sall_users,
			]),
		]);

		$this->mobile_job_m->insert_job([
			'name' => 'sendComment',
			'payload' => json_encode([
				'title' => "Comment on ".$holidayObj->title,  // title is necessary
				'users' => $sall_users,
				'message' => $array['comment']
			]),
		]);
	}


}
