<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class User extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('teacher_m');
        $this->load->model('usertype_m');
        $this->load->model('uattendance_m');
        $this->load->model('manage_salary_m');
        $this->load->model('salary_template_m');
        $this->load->model('salaryoption_m');
        $this->load->model('hourly_template_m');
        $this->load->model('make_payment_m');
        $this->load->model('document_m');
        $this->load->model('leaveapplication_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('profile', $language);
    }

    protected function rules()
    {
        if ($this->session->userdata('usertypeID') == 3) {
            $dobRules = 'trim|max_length[10]|callback_date_valid|xss_clean';
        } else {
            $dobRules = 'trim|required|max_length[10]|callback_date_valid|xss_clean';
        }
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("profile_name"),
                'rules' => 'trim|required|xss_clean|max_length[60]'
            ),
            array(
                'field' => 'dob',
                'label' => $this->lang->line("profile_dob"),
                'rules' => $dobRules,
            ),
            array(
                'field' => 'sex',
                'label' => $this->lang->line("profile_sex"),
                'rules' => 'trim|required|max_length[10]|xss_clean'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("profile_phone"),
                'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("profile_address"),
                'rules' => 'trim|max_length[200]|xss_clean'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("profile_photo"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            ),

            array(
                'field' => 'religion',
                'label' => $this->lang->line("profile_religion"),
                'rules' => 'trim|max_length[25]|xss_clean'
            ),
            array(
                'field' => 'bloodgroup',
                'label' => $this->lang->line("profile_bloodgroup"),
                'rules' => 'trim|max_length[5]|xss_clean'
            ),
            array(
                'field' => 'state',
                'label' => $this->lang->line("profile_state"),
                'rules' => 'trim|max_length[128]|xss_clean'
            ),
            array(
                'field' => 'country',
                'label' => $this->lang->line("profile_country"),
                'rules' => 'trim|max_length[128]|xss_clean'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("profile_email"),
                'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("profile_email"),
                'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
            ),

            array(
                'field' => 'designation',
                'label' => $this->lang->line("profile_designation"),
                'rules' => 'trim|required|max_length[128]|xss_clean'
            ),
            array(
                'field' => 'father_name',
                'label' => $this->lang->line("profile_father_name"),
                'rules' => 'trim|xss_clean|max_length[60]'
            ),
            array(
                'field' => 'mother_name',
                'label' => $this->lang->line("profile_mother_name"),
                'rules' => 'trim|xss_clean|max_length[60]'
            ),
            array(
                'field' => 'father_profession',
                'label' => $this->lang->line("profile_father_name"),
                'rules' => 'trim|xss_clean|max_length[40]'
            ),
            array(
                'field' => 'mother_profession',
                'label' => $this->lang->line("profile_mother_name"),
                'rules' => 'trim|xss_clean|max_length[40]'
            ),
        );
        return $rules;
    }

    public function date_valid($date)
    {
        if ($date) {
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
        return TRUE;
    }

    public function unique_email()
    {
        if ($this->input->post('email')) {
            $username = $this->session->userdata('username');
            if ($username) {
                $tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
                $array = array();
                $i = 0;
                foreach ($tables as $table) {
                    $user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $username));
                    if (customCompute($user)) {
                        $this->form_validation->set_message("unique_email", "%s already exists");
                        $array['permition'][$i] = 'no';
                    } else {
                        $array['permition'][$i] = 'yes';
                    }
                    $i++;
                }
                if (in_array('no', $array['permition'])) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
        return TRUE;
    }

    public function photoupload() {
		$passUserData = array();
		$username = $this->session->userdata('username');
		if($username) {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_single_username($table, array('username' => $username ));
				if(customCompute($user)) {
					$this->form_validation->set_message("unique_email", "%s already exists");
					$passUserData = $user;
				}
			}
		}

		$new_file = "default.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.rand(1, 9999999999) . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(customCompute($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['photo']['tmp_name'];
                $image_info = getimagesize($_FILES['photo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
                    $fileData = $this->upload->data();

                    resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);

					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(customCompute($passUserData)) {
				$this->upload_data['file'] = array('file_name' => $passUserData->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

    public function index_get()
    {
        $usertype = pluck($this->usertype_m->get_usertype(), 'obj', 'usertypeID');
        unset($usertype[1], $usertype[2], $usertype[3], $usertype[4]);

        $myProfile = false;
        if (isset($usertype[$this->session->userdata('usertypeID')])) {
            if (!permissionChecker('user_view')) {
                $myProfile = true;
            }
        }

        if (isset($usertype[$this->session->userdata('usertypeID')]) && $myProfile) {
            $userID = $this->session->userdata('loginuserID');
            $this->getView($userID);
        } else {
            $users = $this->user_m->get_user_by_usertype();
            if (customCompute($users)) {
                $this->retdata['users'] = $users;
            } else {
                $this->retdata['users'] = [];
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function show_get($usertypeID = 0)
    {
        if ($usertypeID == 1) {
            $users = $this->systemadmin_m->get_systemadmin_by_usertype();
        } else if ($usertypeID == 2) {
            $users = $this->teacher_m->getActiveTeachers();
        } else {
            $users = $this->user_m->get_users_by_usertype($usertypeID);
        }
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $users
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($userID = 0)
    {
        $this->getView($userID);
    }

    private function getView($userID)
    {
        if ((int)$userID) {
            $userInfo = $this->user_m->get_user_by_usertype($userID);
            $this->pluckInfo();
            $this->basicInfo($userInfo);
            $this->attendanceInfo($userInfo);
            $this->salaryInfo($userInfo);
            $this->paymentInfo($userInfo);
            $this->documentInfo($userInfo);

            if (customCompute($userInfo)) {
                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => []
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function updateuser_post($userID = '')
    {
        if (inputCall()) {
            $usertypeID = $this->session->userdata('usertypeID');
            $tableArray = array('1' => 'systemadmin', '2' => 'teacher', '3' => 'student', '4' => 'parents');
            if (!isset($tableArray[$this->session->userdata('usertypeID')])) {
                $tableArray[$this->session->userdata('usertypeID')] = 'user';
            }

            $usertypeID   = $this->session->userdata('usertypeID');
            $username     = $this->session->userdata('username');
            $this->data['usertypeID'] = $usertypeID;
            if ($usertypeID == 1) {
                $rules = $this->rules();
                unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
                $this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
            } elseif ($usertypeID == 2) {
                $rules = $this->rules();
                unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
                $this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
            } elseif ($usertypeID == 3) {
                $rules = $this->rules();
                unset($rules[11], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['user'] = $this->studentrelation_m->get_single_student(array('username' => $username, 'srschoolyearID' => $schoolyearID));
            } elseif ($usertypeID == 4) {
                $rules = $this->rules();
                unset($rules[1], $rules[2], $rules[6], $rules[7], $rules[8], $rules[9], $rules[11], $rules[12]);
                $this->data['user'] = $this->parents_m->get_single_parents(array('username' => $username));
            } else {
                $rules = $this->rules();
                unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
                $this->data['user'] = $this->user_m->get_single_user(array('username' => $username));
            }

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->retdata2['validation'] = $this->form_validation->error_array();
				$this->response([
					'status' => false,
					'message' => 'Validation Error',
					'data' => $this->retdata2,
				], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $array = array();
                foreach ($rules as $rulekey => $rule) {
                    if ($rule['field'] == 'dob') {
                        if ($this->input->post($rule['field'])) {
                            $array[$rule['field']] = date("Y-m-d", strtotime($this->input->post($rule['field'])));
                        }
                    } else {
                        $array[$rule['field']] = $this->input->post($rule['field']);
                    }
                }

                if ($usertypeID == 3) {
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $getRelationTableStudent = $this->studentrelation_m->get_single_studentrelation(array('srstudentID' => $this->data['user']->srstudentID, 'srschoolyearID' => $schoolyearID));
                    if (customCompute($getRelationTableStudent)) {
                        $this->student_m->profileRelationUpdate('studentrelation', array('srname' => $this->input->post('name')), $this->data['user']->srstudentID, $schoolyearID);
                    }
                }
               
                $array['photo'] = $this->upload_data['file']['file_name'];
               
                $this->student_m->profileUpdate($tableArray[$usertypeID], $array, $username);
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    private function pluckInfo()
    {
        $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
    }

    private function basicInfo($userInfo)
    {
        if (customCompute($userInfo)) {
            $this->retdata['profile'] = $userInfo;
        } else {
            $this->retdata['profile'] = [];
        }
    }

    private function dayattendance($id = null, $usertypeID = null)
    {
        $schoolyearID       = $this->session->userdata('defaultschoolyearID');
        $attendances       = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id, 'schoolyearID' => $schoolyearID));
        $attendances        = pluck($attendances, 'obj', 'monthyear');
        $schoolYearMonths   = $this->schoolYearMonth($this->data['schoolyearsessionobj']);
        $holidays           = $this->getHolidaysSession();
        $weekends           = $this->getWeekendDaysSession();
        $leaves             = $this->leaveApplicationsDateListByUser($id, $schoolyearID, $usertypeID);

        $attendacneArray = [];
        $totalDayCount   = [];
        if (customCompute($schoolYearMonths)) {
            foreach ($schoolYearMonths as $schoolYearMonth) {
                for ($i = 1; $i <= 31; $i++) {
                    $d = sprintf('%02d', $i);
                    $date = $d . "-" . $schoolYearMonth;

                    if (!isset($totalDayCount['totalholiday'])) {
                        $totalDayCount['totalholiday'] = 0;
                    }

                    if (!isset($totalDayCount['totalweekend'])) {
                        $totalDayCount['totalweekend'] = 0;
                    }

                    if (!isset($totalDayCount['totalleave'])) {
                        $totalDayCount['totalleave'] = 0;
                    }

                    if (!isset($totalDayCount['totalpresent'])) {
                        $totalDayCount['totalpresent'] = 0;
                    }

                    if (!isset($totalDayCount['totallatewithexcuse'])) {
                        $totalDayCount['totallatewithexcuse'] = 0;
                    }

                    if (!isset($totalDayCount['totallate'])) {
                        $totalDayCount['totallate'] = 0;
                    }

                    if (!isset($totalDayCount['totalabsent'])) {
                        $totalDayCount['totalabsent'] = 0;
                    }

                    if (in_array($date, $holidays)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'H';
                        $totalDayCount['totalholiday']++;
                    } elseif (in_array($date, $weekends)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'W';
                        $totalDayCount['totalweekend']++;
                    } elseif (in_array($date, $leaves)) {
                        $attendacneArray[$schoolYearMonth][$i] = 'LA';
                        $totalDayCount['totalleave']++;
                    } else {
                        $a = 'a' . $i;
                        if (isset($attendances[$schoolYearMonth]) && $attendances[$schoolYearMonth]->$a != null) {
                            $attendacneArray[$schoolYearMonth][$i] = $attendances[$schoolYearMonth]->$a;

                            if ($attendances[$schoolYearMonth]->$a == 'P') {
                                $totalDayCount['totalpresent']++;
                            } elseif ($attendances[$schoolYearMonth]->$a == 'LE') {
                                $totalDayCount['totallatewithexcuse']++;
                            } elseif ($attendances[$schoolYearMonth]->$a == 'L') {
                                $totalDayCount['totallate']++;
                            } elseif ($attendances[$schoolYearMonth]->$a == 'A') {
                                $totalDayCount['totalabsent']++;
                            }
                        } else {
                            $attendacneArray[$schoolYearMonth][$i] = 'N/A';
                        }
                    };
                }
            }
        }

        $retArray = ['attendance' => $attendacneArray, 'totalcount' => $totalDayCount];
        return $retArray;
    }

    private function schoolYearMonth($schoolYear, $keyExist = false)
    {
        $dateArray = [];
        $startDate    = (new DateTime($schoolYear->startingdate))->modify('first day of this month');
        $endDate      = (new DateTime($schoolYear->endingdate))->modify('last day of this month');
        $dateInterval = DateInterval::createFromDateString('1 month');
        $datePeriods   = new DatePeriod($startDate, $dateInterval, $endDate);

        if (customCompute($datePeriods)) {
            foreach ($datePeriods as $datePeriod) {
                if ($keyExist) {
                    $dateArray[] = ['monthkey' => $datePeriod->format("m") . '-' . $datePeriod->format("Y"), 'monthname' => $datePeriod->format("M")];
                } else {
                    $dateArray[] = $datePeriod->format("m-Y");
                }
            }
        }
        return $dateArray;
    }

    private function leaveApplicationsDateListByUser($studentID, $schoolyearID, $usertypeID)
    {
        $leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID' => $studentID, 'create_usertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'status' => 1));
        $retArray = [];
        if (customCompute($leaveapplications)) {
            $oneday    = 60 * 60 * 24;
            foreach ($leaveapplications as $leaveapplication) {
                for ($i = strtotime($leaveapplication->from_date); $i <= strtotime($leaveapplication->to_date); $i = ($i + $oneday)) {
                    $retArray[] = date('d-m-Y', $i);
                }
            }
        }
        return $retArray;
    }

    public function attendanceInfo($userInfo)
    {
        if (customCompute($userInfo)) {
            $userID         = $userInfo->userID;
            $usertypeID     = $userInfo->usertypeID;
            $attendance = $this->dayattendance($userID, $usertypeID);
            $this->retdata['attendancesmonths'] = $this->schoolYearMonth($this->data['schoolyearsessionobj'], true);
            $this->retdata['attendance'] = $attendance['attendance'];
            $this->retdata['totalcount'] = $attendance['totalcount'];
        } else {
            $this->retdata['attendance'] = [];
            $this->retdata['totalcount'] = [];
        }
    }

    private function salaryInfo($userInfo)
    {
        if (customCompute($userInfo)) {
            $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $userInfo->usertypeID, 'userID' => $userInfo->userID));
            if (customCompute($manageSalary)) {
                $this->retdata['manage_salary'] = $manageSalary;
                if ($manageSalary->salary == 1) {
                    $this->retdata['salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $manageSalary->template));
                    if ($this->retdata['salary_template']) {
                        $this->db->order_by("salary_optionID", "asc");
                        $this->retdata['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salary_templateID' => $manageSalary->template));

                        $grosssalary = 0;
                        $totaldeduction = 0;
                        $netsalary = $this->retdata['salary_template']->basic_salary;
                        $orginalNetsalary = $this->retdata['salary_template']->basic_salary;
                        $grosssalarylist = array();
                        $totaldeductionlist = array();

                        if (customCompute($this->retdata['salaryoptions'])) {
                            foreach ($this->retdata['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                if ($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    $grosssalary += $salaryOption->label_amount;
                                    $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                } elseif ($salaryOption->option_type == 2) {
                                    $netsalary -= $salaryOption->label_amount;
                                    $totaldeduction += $salaryOption->label_amount;
                                    $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                }
                            }
                        }

                        $this->retdata['grosssalary'] = ($orginalNetsalary + $grosssalary);
                        $this->retdata['totaldeduction'] = $totaldeduction;
                        $this->retdata['netsalary'] = $netsalary;
                    } else {
                        $this->retdata['salary_template'] = [];
                        $this->retdata['salaryoptions'] = [];
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = 0;
                    }
                } elseif ($manageSalary->salary == 2) {
                    $this->retdata['hourly_salary'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID' => $manageSalary->template));
                    if (customCompute($this->retdata['hourly_salary'])) {
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = $this->retdata['hourly_salary']->hourly_rate;
                    } else {
                        $this->retdata['hourly_salary'] = [];
                        $this->retdata['grosssalary'] = 0;
                        $this->retdata['totaldeduction'] = 0;
                        $this->retdata['netsalary'] = 0;
                    }
                }
            } else {
                $this->retdata['manage_salary'] = [];
                $this->retdata['salary_template'] = [];
                $this->retdata['salaryoptions'] = [];
                $this->retdata['hourly_salary'] = [];
                $this->retdata['grosssalary'] = 0;
                $this->retdata['totaldeduction'] = 0;
                $this->retdata['netsalary'] = 0;
            }
        } else {
            $this->retdata['manage_salary'] = [];
            $this->retdata['salary_template'] = [];
            $this->retdata['salaryoptions'] = [];
            $this->retdata['hourly_salary'] = [];
            $this->retdata['grosssalary'] = 0;
            $this->retdata['totaldeduction'] = 0;
            $this->retdata['netsalary'] = 0;
        }
    }

    private function paymentInfo($userInfo)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if (customCompute($userInfo)) {
            $this->retdata['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $userInfo->usertypeID, 'userID' => $userInfo->userID, 'schoolyearID' => $schoolyearID));
        } else {
            $this->retdata['make_payments'] = [];
        }
    }

    private function documentInfo($userInfo)
    {
        if (customCompute($userInfo)) {
            $this->retdata['documents'] = $this->document_m->get_order_by_document(array('userID' => $userInfo->userID, 'usertypeID' => $userInfo->usertypeID));
        } else {
            $this->retdata['documents'] = [];
        }
    }

    
}
