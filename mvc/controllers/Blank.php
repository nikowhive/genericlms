<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Blank extends Admin_Controller {
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

	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->data["subview"] = "blank/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function new_page() {
		$this->data["subview"] = "blank/new_page";
		$this->load->view('_layout_main', $this->data);
	}

	public function report() {
		$this->data["subview"] = "blank/report";
		$this->load->view('_layout_empty', $this->data);
	}

	public function bgmsm_report() {
		$this->data["subview"] = "blank/bgmsm_report";
		$this->load->view('_layout_empty', $this->data);
	}

	public function report_11_12() {
		$this->data["subview"] = "blank/report_11_12";
		$this->load->view('_layout_empty', $this->data);
	}

	public function quizzes() {
		$this->data["subview"] = "blank/quizzes";
		$this->load->view('_layout_main', $this->data);
	}

	public function quizzes2() {
		$this->data["subview"] = "blank/quizzes2";
		$this->load->view('_layout_main', $this->data);
	}

	public function social_dashboard() {
		$this->data["subview"] = "blank/social";
		$this->load->view('_layout_feed', $this->data);
	}

	public function attendance() {
		$this->data["subview"] = "blank/attendance";
		$this->load->view('_layout_main', $this->data);
	}

	public function student_attendance() {
		$this->data["subview"] = "blank/student_attendance";
		$this->load->view('_layout_main', $this->data);
	}

	public function take_attendance() {
		$this->data["subview"] = "blank/take_attendance";
		$this->load->view('_layout_main', $this->data);
	}

	public function activities() {
		$this->data["subview"] = "blank/activities";
		$this->load->view('_layout_main', $this->data);
	}

	public function coursedetail() {
		$this->data["subview"] = "blank/coursedetail";
		$this->load->view('_layout_main', $this->data);
	}
}