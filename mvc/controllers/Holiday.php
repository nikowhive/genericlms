<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Holiday extends Admin_Controller
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
		$this->load->model("job_m");
		$this->load->model("mobile_job_m");
		$this->load->model("holiday_m");
		$this->load->model("feed_m");
		$this->load->model("alert_m");
		$this->load->model("user_m");
		$this->load->model("student_m");
		$this->load->model("teacher_m");
		$this->load->model("parents_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("systemadmin_m");
		$this->load->model("holiday_media_m");
		$this->load->model("holiday_comment_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('holiday', $language);
		$this->load->library("pagination");
		$this->db->cache_off();
	}

	public function index()
	{

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$totalCounts = $this->holiday_m->getCount();

		$config = array();
		$config["base_url"] = base_url() . "holiday/index";
		$config["total_rows"] = $totalCounts;
		$config["per_page"] = 20;
		$config["uri_segment"] = 3;

		$this->pagination->initialize($config);
		$this->data["links"] = $this->pagination->create_links();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->data['userType'] = $this->session->userdata('usertypeID');
		$this->data['schoolyearID'] = $this->session->userdata("defaultschoolyearID");

		$isAdmin = $this->session->userdata('usertypeID') == 1 ? true : false;
		$holidays = $this->holiday_m->getRecentHolidays($config["per_page"], $page, $schoolyearID,'','', $isAdmin);

		$comments = [];
		$holidaysMedia = [];
		foreach ($holidays as $key => $holiday) {
			$holidayID = $holiday->holidayID;
			$holiday_media = $this->holiday_media_m->get_order_by_holiday_media(['holidayID' => $holidayID]);
			$n_e_media = array();
			foreach ($holiday_media as $media) {
				$n_e_media[] = $media->attachment;
			}
			$holidays[$key]->media = $n_e_media;

			$holiday_comments_count = count($this->holiday_comment_m->paginatedHolidayComments('','',['holidayID' => $holidayID]));
			$holidays[$key]->comment_count = $holiday_comments_count;

			$holiday_comments = $this->holiday_comment_m->paginatedHolidayComments(5,0,['holidayID' => $holidayID]);
			
			if(customCompute($holiday_comments)){
				$reverse = array_reverse($holiday_comments);
				$comments[$holidayID] = $reverse;
			}

			if(customCompute($holiday_media)){
				$holidaysMedia[$holidayID] = $holiday_media;

			}
		}

		$this->data['feeds'] = $holidays;
		$this->data['user'] = getAllSelectUser();
		$this->data['comments'] = $comments;
		$this->data['holidaysMedia'] = $holidaysMedia;

		$this->data["subview"] = "holiday/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function getMoreHolidayData()
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->data['userType'] = $this->session->userdata('usertypeID');
		$this->data['schoolyearID'] = $this->session->userdata("defaultschoolyearID");
		$isAdmin = $this->session->userdata('usertypeID') == 1 ? true : false;
		$holidays = $this->holiday_m->getRecentHolidays(20, $page, $schoolyearID, '', '', $isAdmin);

		$comments = [];
		$holidaysMedia = [];
		foreach ($holidays as $key => $holiday) {
			$holidayID = $holiday->holidayID;
			$holiday_media = $this->holiday_media_m->get_order_by_holiday_media(['holidayID' => $holidayID]);
			$n_e_media = array();
			foreach ($holiday_media as $media) {
				$n_e_media[] = $media->attachment;
			}
			$holidays[$key]->media = $n_e_media;

			$holiday_comments_count = count($this->holiday_comment_m->paginatedHolidayComments('','',['holidayID' => $holidayID]));
			$holidays[$key]->comment_count = $holiday_comments_count;

			$holiday_comments = $this->holiday_comment_m->paginatedHolidayComments(5,0,['holidayID' => $holidayID]);
			
			if(customCompute($holiday_comments)){
				$reverse = array_reverse($holiday_comments);
				$comments[$holidayID] = $reverse;
			}

			if(customCompute($holiday_media)){
				$holidaysMedia[$holidayID] = $holiday_media;
			}
		}

		$this->data['feeds'] = $holidays;
		$this->data['user'] = getAllSelectUser();
		$this->data['comments'] = $comments;
		$this->data['holidaysMedia'] = $holidaysMedia;

		if ($this->data['feeds']) {
			echo $this->load->view('holiday/autoload_holiday', $this->data, true);
			exit;
		} else {
			showBadRequest(null, "No data.");
		}
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("holiday_title"),
				'rules' => 'trim|required|xss_clean|max_length[75]|min_length[3]'
			),
			array(
				'field' => 'fdate',
				'label' => $this->lang->line("holiday_fdate"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
			),
			array(
				'field' => 'tdate',
				'label' => $this->lang->line("holiday_tdate"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_todate_valid'
			),
			array(
				'field' => 'published_date',
				'label' => $this->lang->line("holiday_"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
			),
			array(
				'field' => 'photos[]',
				'label' => $this->lang->line("holiday_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
			),
			array(
				'field' => 'holiday_details',
				'label' => $this->lang->line("holiday_details"),
				'rules' => 'trim|required|xss_clean'
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
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("holiday_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("holiday_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'holidayID',
				'label' => $this->lang->line("holiday_holidayID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	public function unique_data($data)
	{
		if ($data != '') {
			if ($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function photoupload()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$holiday = array();
		if ((int)$id) {
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
					return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
				return FALSE;
			}
		} else {
			if (customCompute($holiday)) {
				$this->upload_data['file'] = array('file_name' => $holiday->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	public function add()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js',
				)
			);

			if ($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data['form_validation'] = validation_errors();
					$this->data["subview"] = "holiday/add";
					$this->load->view('_layout_main', $this->data);
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
					$array['photo'] = '';
					$array['create_date'] = date('Y-m-d H:i:s');
					$array['create_userID'] = $this->session->userdata('loginuserID');
					$array['create_usertypeID'] = $this->session->userdata('usertypeID');
					$array["enable_comment"] = $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2;

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

					if (!empty($holidayID)) {
						$this->alert_m->insert_alert(array('itemID' => $holidayID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'holiday'));
					    $this->feed_m->insert_feed(
							array(
								'itemID'         => $holidayID,
								'userID'         => $this->session->userdata("loginuserID"),
								'usertypeID'     => $this->session->userdata('usertypeID'),
								'itemname'       => 'holiday',
								'schoolyearID'   => $this->session->userdata('defaultschoolyearID'),
								'published'      => $published,
								'published_date' => date("Y-m-d", strtotime($this->input->post("published_date"))),
								'status'         => 'public'
							)
						);
					}

					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("holiday/index"));
				}
			} else {
				$this->data["subview"] = "holiday/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
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

	private function _upload_images()
	{
		if ($_FILES['photos']['name'][0] !== "") {
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
					$uploadData[$i]['attachment'] = $fileData['file_name'];
					$uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
				}
			}
			return $uploadData;
		} else {
			return array();
		}
	}

	function sendFcmNotification($data)
	{
		$registered_ids = pluck($this->fcmtoken_m->get_order_by_fcm_token(), 'fcm_token');
		$message['data'] = [
			'message' => $data['details'],
			'title' => $data['title'],
			'photo' => base_url('/uploads/holiday/' . $data['photo']),
			'action' => 'holiday'
		];
		chunk_push_notification($registered_ids, $message);
	}

	public function edit()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js',
				)
			);

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
				$this->data['holiday_media'] = $this->holiday_media_m->get_order_by_holiday_media(['holidayID' => $id]);
			
				if ($this->data['holiday']) {
					if ($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "holiday/edit";
							$this->load->view('_layout_main', $this->data);
						} else {

							$currentDate = date('d-m-Y');
							$publishDate = $this->input->post("published_date");

							if ($publishDate <= $currentDate) {
								$published = 1;
							} else {
								$published = 2;
							}

							$array = array(
								"title" => $this->input->post("title"),
								"details" => $this->input->post("holiday_details"),
								"fdate" => date("Y-m-d", strtotime($this->input->post("fdate"))),
								"tdate" => date("Y-m-d", strtotime($this->input->post("tdate"))),
								"published_date" => date("Y-m-d", strtotime($this->input->post("published_date"))),
								"published" => $published,
								"enable_comment" => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2
							);
							$this->holiday_m->update_holiday($array, $id);

							$photos = $this->upload_data['files'];
							if (customCompute($photos)) {
								foreach ($photos as $key => $photo) {
									$photos[$key]['holidayID'] = $id;
								}
								$this->holiday_media_m->insert_batch_holiday_media($photos);
							}

							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("holiday/index"));
						}
					} else {
						$this->data["subview"] = "holiday/edit";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view()
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
			if ($this->data['holiday']) {
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

				$this->data["subview"] = "holiday/view";
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

	public function deleteImage()
	{
		if ($this->input->post('id')) {
			$id = $this->input->post('id');
			$holidayMedia = $this->holiday_media_m->get_single_holiday_media(array('id' => $id));
			if($this->holiday_media_m->delete_holiday_media($id)){
				if (file_exists(FCPATH . 'uploads/holiday/' . $holidayMedia->attachment )) {
					unlink(FCPATH . 'uploads/holiday/' . $holidayMedia->attachment );
				}
				$retArray['status'] = true;
				$retArray['message'] = $this->lang->line('menu_success');
				echo json_encode($retArray);
				exit;
			}
			
		}
	}

	public function delete()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
				if (customCompute($this->data['holiday'])) {
					if (config_item('demo') == FALSE) {
						if ($this->data['holiday']->photo != 'holiday.png' && $this->data['holiday']->photo != '') {
							if (file_exists(FCPATH . 'uploads/holiday/' . $this->data['holiday']->photo)) {
								unlink(FCPATH . 'uploads/holiday/' . $this->data['holiday']->photo);
							}
						}
					}
					$feed = $this->feed_m->get_single_feed(['itemID' => $id,'itemname' => 'holiday']);
					$this->holiday_m->delete_holiday($id);
					if($feed){
						$this->feed_m->delete_feed($feed->feedID);
					}
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("holiday/index"));
				} else {
					redirect(base_url("holiday/index"));
				}
			} else {
				redirect(base_url("holiday/index"));
			}
		} else {
			redirect(base_url("holiday/index"));
		}
	}

	public function date_valid($date)
	{
		if (strlen($date) < 10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
			return FALSE;
		} else {
			$arr = explode("-", $date);
			$dd = $arr[0];
			$mm = $arr[1];
			$yyyy = $arr[2];
			if (checkdate($mm, $dd, $yyyy)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
				return FALSE;
			}
		}
	}

	public function todate_valid($date)
	{
		$fdate = $this->input->post('fdate');
		if (strlen($date) < 10) {
			$this->form_validation->set_message("todate_valid", "%s is not valid dd-mm-yyyy");
			return FALSE;
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
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("todate_valid", "%s is not valid dd-mm-yyyy");
				return FALSE;
			}
		}
	}

	public function print_preview()
	{
		if (permissionChecker('holiday_view')) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
				if (customCompute($this->data['holiday'])) {
					$userID = $this->data['holiday']->create_userID;
					$usertypeID = $this->data['holiday']->create_usertypeID;
					$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
					$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));
					$this->data['usertype'] = $usertype->usertype;
					$this->reportPDF('holidaymodule.css', $this->data, 'holiday/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('holiday_view')) {
			if ($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$id = $this->input->post('holidayID');
					if ((int)$id) {
						$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
						if (customCompute($this->data['holiday'])) {
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$userID = $this->data['holiday']->create_userID;
							$usertypeID = $this->data['holiday']->create_usertypeID;
							$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
							$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));
							$this->data['usertype'] = $usertype->usertype;
							$this->reportSendToMail('holidaymodule.css', $this->data, 'holiday/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
							exit;
						} else {
							$retArray['message'] = $this->lang->line('holiday_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('holiday_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('holiday_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('holiday_permission');
			echo json_encode($retArray);
			exit;
		}
	}
	public function media()
	{
		$holiday_id = $this->input->get('holiday_id');
		$holiday_media = $this->holiday_media_m->get_order_by_holiday_media(['holidayID' => $holiday_id]);
		$this->data['holiday_media'] = (is_object($holiday_media) or is_array($holiday_media)) ? pluck_multi_array($holiday_media, 'obj', 'holidayID') : $holiday_media;
		echo json_encode($this->data['holiday_media']);
	}

	public function postChangeHolidayStatus($id)
	{

		$holiday = $this->holiday_m->get_single_holiday(['holidayID' => $id]);
		$array = [
			'published' => $holiday->published == 2 ? 1 : 2,
			'published_date' => date('Y-m-d')
		];
		
		if($this->holiday_m->update_holiday($array, $id)){
			$feed = $this->feed_m->get_single_feed(array('itemID' => $id, 'itemname' => 'holiday'));

			$feedarray = array(
				"itemID" => $id,
				"userID" => $this->session->userdata("loginuserID"),
				"usertypeID" => $this->session->userdata("usertypeID"),
				"itemname" => 'holiday',
			);
			if (!customCompute($feed)) {
				$feedarray['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
				$feedarray['published'] = $array['published'];
				$feedarray['published_date'] = $array['published_date'];
				$feedarray['status']  = 'public';
				$this->feed_m->insert_feed($feedarray);
			}else{
				$this->feed_m->update_feed(['published_date' => $array['published_date'],'published' => $array['published']],$feed->feedID);
			}

			echo true;
	    }
	}

    public function comment()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ($_POST) {
            $array['holidayID'] = $this->input->post('activity_id');
            $array['comment'] = $this->input->post('comment');
            $array['schoolyearID'] = $schoolyearID;
            $array['userID'] = $this->session->userdata("loginuserID");
            $array['usertypeID'] = $this->session->userdata("usertypeID");
            $array['create_date'] = date("Y-m-d H:i:s");
            $data = $this->holiday_comment_m->insert_holiday_comment($array);
			if($data){
                $this->pushNotificationOfComment($array);
			}
            echo $data;
        }
    }

	public function delete_comment()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {

			$id = $this->input->post('id');
			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

			if ((int)$id) {
				$comment = $this->holiday_comment_m->get_holiday_comment($id);
				$holiday = $this->holiday_m->get_holiday($comment->holidayID);
				if (($usertypeID == $holiday->create_usertypeID && $userID == $holiday->create_userID) || ($usertypeID == 1)) {
					$this->holiday_comment_m->delete_holiday_comment($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				}

				$retArray['status'] = TRUE;;
				$retArray['message'] = $this->lang->line('menu_success');
				echo json_encode($retArray);
				exit;
			} else {
				redirect(base_url("holiday/index"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
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
		$startDate = date('Y-m-d', strtotime("-60 days", strtotime($latestdate)));
		$endDate = $latestdate;

		return [$startDate, $endDate];
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

	public function getComment(){

		$commentID = $this->input->get('commentID');
		$holidayID = $this->input->get('holidayID');

		$holiday_comment = $this->holiday_comment_m->get_single_holiday_comment(['commentID' => $commentID,'holidayID' => $holidayID]);
		 
		if($holiday_comment){
			 $this->data['comment'] = $holiday_comment->comment;  
			 $this->data['commentID'] = $commentID;
			 echo $this->load->view('holiday/comment_template', $this->data, true);
		}else{
			 $this->data['comment'] = ''; 
			 $this->data['commentID'] = '';  
			 echo $this->load->view('holiday/comment_template', $this->data, true);
		}

		exit;

    }

	public function editComment(){
		
		$array['comment']      = $this->input->post('comment');
		$commentID      = $this->input->post('commentID');
		
		$data = $this->holiday_comment_m->update_holiday_comment($array,$commentID);
		if($data){
			echo $array['comment'];
		}else{
			echo false;
		}
	}

	public function getMoreHolidayCommentData(){
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$holidayID = $this->input->get('holidayID');
		$holiday_comments = $this->holiday_comment_m->paginatedHolidayComments(5,$page,['holidayID' => $holidayID]);
		$reverse = array_reverse($holiday_comments);
		$this->data['comments'] = $reverse;
		if ($holiday_comments) {
			echo $this->load->view('holiday/autoload_holiday_comment', $this->data, true);
			exit;
		} else {
			showBadRequest(null, "No data.");
		}			
	}

}
