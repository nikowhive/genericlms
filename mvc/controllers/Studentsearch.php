<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentsearch extends Admin_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("sattendance_m");
		$this->load->model("attendance_note_m");
		$this->load->model("teacher_m");
		$this->load->model("teachersubject_m");
		$this->load->model("teachersection_m");
		$this->load->model("classes_m");
		$this->load->model("conversation_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("notice_m");
		$this->load->model("alert_m");
		$this->load->model("job_m");
		$this->load->model("user_m");
		$this->load->model("usertype_m");
		$this->load->model("section_m");
		$this->load->model("setting_m");
		$this->load->model('studentgroup_m');
		$this->load->model('subject_m');
		$this->load->model('schoolyear_m');
		$this->load->model('subjectteacher_m');
		$this->data['setting'] = $this->setting_m->get_setting();

		if($this->data['setting']->attendance == "subject") {
			$this->load->model("subjectattendance_m");
		}
		$language = $this->session->userdata('lang');
		$this->lang->load('searchstudent_lang', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("attendance_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("attendance_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date|callback_check_holiday|callback_check_weekendday|callback_check_session_year_date'
			)
		);
		return $rules;
	}

	public function index(){
	//$this->data['panel_title'] = 'Search Student';	
	$this->data['headerassets'] = array(
		'css' => array(
			'assets/select2/css/select2.css',
			'assets/select2/css/select2-bootstrap.css',
			'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
			'assets/jqueryUI/jqueryui.css'
		),
		'js' => array(
			'assets/select2/select2.js',
			'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
			'assets/jqueryUI/jqueryui.min.js'
		)
		);

		$this->data['holidays'] = $this->getHolidaysano();
		$this->data['startingtime'] = date('Y,m,d',strtotime($this->data['schoolyearsessionobj']->startingdate));
        $this->data['endingtime'] = date('Y,m,d',strtotime($this->data['schoolyearsessionobj']->endingdate));
		$this->data['userType'] = $this->session->userdata('usertypeID');
		$classobj = $this->classes_m->get_class_by_teacher();
		$this->data['students'] = $this->student_m->get_allstudentsjson();
		if($this->session->userdata('usertypeID')==2){
		    $this->data['classesID'] = $classobj->classesID;
		    $this->data['sectionobj']  = $this->section_m->get_single_section(array('teacherID'=>$this->session->userdata('loginuserID')));
		    $teacherobjs = $this->subjectteacher_m->get_classes_by_teacher();
			$this->data['teacherobjs'] = $teacherobjs;
			//print_r($this->data['sectionobj']);die();
			$this->data["subview"] = "studentsearch/studentviewlist";
	    } else {
	    	if(!$this->uri->segment(3) && !$this->uri->segment(4)){
				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "studentsearch/studentviewlistadmin";
		    } else if($this->uri->segment(3) && !$this->uri->segment(4)){
		    	$this->data['classesID'] = $this->uri->segment(3);
				//$this->data['attdate'] = $this->uri->segment(4);
            	$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "studentsearch/studentviewlistadmin";
		    } else{
		    	$this->data['classesID'] = $this->uri->segment(3);
				$this->data['sectionID'] = $this->uri->segment(4);
				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "studentsearch/studentviewlist";
		    }
	    }
		
		$this->load->view('_layout_main', $this->data);
	}

	public function take_studentview(){
		$classesID = $this->input->post('id');
		$sectionID = $this->input->post('idsection');
		//$attendancedate = $this->input->post('attdate');
		//$subject = $this->teachersubject_m->get_subject_by_teacher();
		$students = $this->student_m->get_order_by_student_with_section($classesID, $this->session->userdata('defaultschoolyearID'), $sectionID);
		$classes = $this->classes_m->get_classes($classesID)->classes;
		$studentno = $this->student_m->get_student_number($sectionID);
		$teacherprofile = $this->teachersection_m->get_sectionteacher($sectionID);
		$sectionname = $this->section_m->getSectionByID($sectionID);
		//print_r($attendancep);die();
		echo '<h4><b>Class Student List</b></h4>
        <div class="card mt-3 card--attendance">
            <div class="card-header">
                <div class="row row-md-flex">
                    <div class="col-md-5">
					<div class="media-block media-block-alignCenter">
					
						<figure class="avatar__figure">
						<span class="avatar__image">
						<img
	                        src="'.base_url().'/uploads/images/'.$teacherprofile->photo.'"
	                        alt=""  
	                      />
						</span>
						
						
						</figure>
						<div class="media-block-body">
						<h3 class="card-title mb-3 mb-lg-0">
                        '.$classes.' <span class="pill pill--flat pill--sm">'.$sectionname.'</span></h3>
                        <div class="mt-2 ">'.$teacherprofile->name.'</div>
						</div>
					</div>
                        
                    </div>
                    <div class="col-md-4 attendance-stats">
                        <div>'.$studentno.' Students</div>
                            
                        </div>
                        <div class="col-md-3 attendance-action">
                        
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="attendee-lists">
                    <div hidden data-value="'.$studentno.'" id="hiddenchron"></div>';
            
        $i=0;
        foreach($students as $student){ 
            echo '<div class="attendee-lists-item">
                      <div class="media-block">
                          <figure class="avatar__figure">
                          <span class="avatar__image">
	                      <img
	                        src="'.base_url().'/uploads/images/'.$student->photo.'"
	                        alt=""
	                      />
                          </span>
                          </figure>
                      <div class="media-block-body">
                          <div class="media-content">
                          <h4 class="title">
                          <a href="'.base_url().'student/view/'.$student->studentID.'/'.$student->classesID.'" >'.$student->name.'</a>
                          <em class="rollnumber">Roll # <b>'.$student->roll.'</b></em>
                          </h4>
                      </div>
                      </div>
                  </div>
                  </div>';
              $i++; 

        }
        echo '</div>
              </div>
              </div>
              </div></div>';
	}

	public function sectionall() {
		$classesID = $this->input->post('id');
		if((int)$classesID) {
			$sections = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("attendance_select_section"),"</option>";
			if(customCompute($sections)) {
				foreach ($sections as $key => $section) {
					echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
				}
			}
		}
	}

	public function classsectionview() {
		$id = $this->input->post('id');
		
		if((int)$id) {
			$sections = $this->section_m->get_order_by_section(array('classesID' => $id));

			$classes = $this->classes_m->get_classes($id)->classes;
			echo '<h4><b>Class '.$classes.'</b></h4>';
			foreach($sections as $section){
				$teacherprofile = $this->teachersection_m->get_sectionteacher($section->sectionID);
				//print_r($this->section_m->get_section($section->sectionID));die();
				$attdate = $this->input->post('attdate');
				$studentno = $this->student_m->get_student_number($section->sectionID);
				
				echo   '<div class="card mt-3 card--attendance">
					        <div class="card-header ">
					            <div class="row row-md-flex">
					                <div class="col-md-5">
									<div class ="media-block mb-3 mb-lg-0 media-block-alignCenter">
									<figure class="avatar__figure">
									<span class="avatar__image">
									<img
										src="'.base_url().'/uploads/images/'.$teacherprofile->photo.'"
										alt="" class="avatar-img"
									/>
									</span>
									
									
									</figure>
										 
										<div class="media-block-body">
										<h3 class="card-title mb-3 mb-lg-0">'.$classes.'<span class="pill pill--flat pill--sm">'.$section->section.
					                    '</span></h3>
										<div class="mt-2">'.$teacherprofile->name.'</div>
										</div>
									</div>
				                      
					                      
				                    </div>
				                    <div class="col-md-4 attendance-stats">
				                        <div>'.$studentno.' Students</div>
				                            
				                    </div>
			                        <div class="col-md-3 attendance-action">
			                            <a href="'.base_url().'studentsearch/index/'.$id.'/'.$section->sectionID.'"  class="btn-link btn">
				                        View Student 
				                        <i class="fa fa-2x fa-angle-right ml-3"></i>
				                    </a>
			                        </div>
					            </div>
					        </div>
					    </div>';	     
			}    
		}
	}
}