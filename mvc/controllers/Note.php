<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;

class Note extends Admin_Controller {
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
	function __construct() 
	{
		parent::__construct();
		$this->load->model("job_m");
		$this->load->model("note_m");
		$this->load->model("alert_m");
		$this->load->model("pushsubscription_m");
		$this->load->model("student_m");
		$this->load->model("fcmtoken_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('note', $language);
        	$this->load->library("pagination");
		$this->db->cache_off();
	}

	public function index() 
	{
        $config = array();
        $config["base_url"] = base_url() . "note/index";
        $config["total_rows"] = $this->note_m->getCount();
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;

        $this->data['userType'] = $this->session->userdata('usertypeID');
        $this->data['schoolyearID'] = $this->session->userdata("defaultschoolyearID");
        $notes = [];
        $userID = $this->session->userdata('loginuserID');
        $usertype = $this->data['userType'];
        $allnotes = $this->note_m->get_query_note(array('userID'=> $userID));
        
        $this->data['feeds'] = $allnotes;
       
        $this->data["subview"] = "note/index";
        $this->load->view('_layout_main', $this->data);
	}

	protected function rules()
	{
		$rules = array(
				array(
					'field' => 'note',
					'label' => $this->lang->line("note_note"),
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
				'label' => $this->lang->line("note_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("note_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("note_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'noteID',
				'label' => $this->lang->line("note_noteID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	public function unique_data($data)
	{
		if($data != '') {
			if($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function add() 
	{
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID'))) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js'
				)
			);
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data['form_validation'] = validation_errors();
					$this->data["subview"] = "note/add";
					$this->load->view('_layout_main', $this->data);
				} else {

					$array = array(
						"note" => $this->input->post("note"),
						'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
						"create_date" => date("Y-m-d H:i:s"),
						"userID" => $this->session->userdata('loginuserID'),
						"usertypeID" => $this->session->userdata('usertypeID')
					);
					$this->note_m->insert_note($array);
        			$sql = $this->db->last_query();

					$noteID = $this->db->insert_id();
					if(!empty($noteID)) {
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					}else{
						$this->session->set_flashdata('error', $this->lang->line('menu_notice'));
					}
					redirect(base_url("note/index"));
				}
			} else {
				$this->data["subview"] = "note/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function feed_add() 
	{
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID'))) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js'
				)
			);
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data['form_validation'] = validation_errors();
					$this->data["subview"] = "note/add";
					$this->load->view('_layout_main', $this->data);
				} else {

					$array = array(
						"note" => $this->input->post("note"),
						'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
						"create_date" => date("Y-m-d H:i:s"),
						"userID" => $this->session->userdata('loginuserID'),
						"usertypeID" => $this->session->userdata('usertypeID')
					);
					$this->note_m->insert_note($array);
        			$sql = $this->db->last_query();

					$noteID = $this->db->insert_id();
					if(!empty($noteID)) {
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					}else{
						$this->session->set_flashdata('error', $this->lang->line('menu_notice'));
					}
					redirect(base_url("feed/index"));
				}
			} else {
				$this->data["subview"] = "feed/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function ajax_note_update() 
	{
		$note = $this->input->post('note');
		$noteID = $this->input->post('nid');
        $userID = $this->session->userdata('loginuserID');

        $array = array(
        	'note'=> $note
		);
		$res = $this->note_m->update_note($array, $noteID);
        $notes = $this->note_m->get_query_note(array('userID'=> $userID));
		echo json_encode($notes);
	}

	public function ajax_note_delete() 
	{
        $userID = $this->session->userdata('loginuserID');
		$noteID = $this->input->post('noteID');
		$note = $this->note_m->get_single_note(array('noteID' => $noteID));
		if($note) {
			$this->note_m->delete_note($noteID);
		}

        $notes = $this->note_m->get_query_note(array('userID'=> $userID));
		echo json_encode($notes);
	}

	public function ajax_note_add() 
	{
		$note = $this->input->post('note');
        $userID = $this->session->userdata('loginuserID');

        $array = array(
        	'note'=> $note,
			'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
			"create_date" => date("Y-m-d H:i:s"),
			"userID" => $userID,
			"usertypeID" => $this->session->userdata('usertypeID')
		);
		$res = $this->note_m->insert_note($array);
		$sql = $this->db->last_query();
        $notes = $this->note_m->get_query_note(array('userID'=> $userID));
		echo json_encode($notes);
	}

	public function feed_del($nid)
	{
		if((int)$nid)
		{
			$note = $this->note_m->get_single_note(array('noteID' => $nid));
			if($note) {
				$this->note_m->delete_note($nid);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			}else
			{
				$this->session->set_flashdata('error', $this->lang->line('menu_success'));
			}
		}
		redirect(base_url("feed/index"));
	}

	function pushNotification($array) 
	{
		$this->job_m->insert_job([
			'name' => 'sendNote',
			'payload' => json_encode([
				'title' => $array['title'],  // title is necessary
				'users' => $array['users'],
			]),
		]);
	}

	public function edit() 
	{
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js'
				)
			);
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['note'] = $this->note_m->get_single_note(array('noteID' => $id, 'schoolyearID' => $schoolyearID));
				if($this->data['note']) {
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "note/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array(
								"title" => $this->input->post("title"),
								"note" => $this->input->post("note"),
								"date" => date("Y-m-d", strtotime($this->input->post("date")))
							);

							$this->note_m->update_note($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("note/index"));
						}
					} else {
						$this->data["subview"] = "note/edit";
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

	public function delete() 
	{
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['note'] = $this->note_m->get_single_note(array('noteID' => $id, 'schoolyearID' => $schoolyearID));
				if($this->data['note']) {
					$this->note_m->delete_note($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("note/index"));
				} else {
					redirect(base_url("note/index"));
				}
			} else {
				redirect(base_url("note/index"));
			}
		} else {
			redirect(base_url("note/index"));
		}
	}

}
