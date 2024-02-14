<?php 

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use \PhpOffice\PhpSpreadsheet\Style\Border;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Admin_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('user_m');
        $this->load->model('parents_m');
        $this->load->model('systemadmin_m');
        $this->load->model('subject_m');
        $this->load->model('usertype_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('searchpaymentfeesreport', $language);
	}


	public function index() {

        if(!isset($_REQUEST['text'])){
            $this->data["subview"] = "error";
		    $this->load->view('_layout_main', $this->data);
           
        }else{
            $text = $_REQUEST['text'];
            list($result, $resultList )= $this->getUsers($text,7,0);
            $this->data['results'] = $resultList;
            $this->data["subview"] = "search/index";
            $this->load->view('_layout_main', $this->data);
        }
        
	}

    

    public function searchUsers(){

       $text = $this->input->post('phrase');
       list($result, $resultList )= $this->getUsers($text,7,0);

       //add the header here
        header('Content-Type: application/json');
        echo json_encode( $result );

    }

    public function getUsers($text,$limit,$page){

        $userType = $this->session->userdata('usertypeID');

        $students      = [];
        $users         = [];
        $teachers      = [];
        $parents       = [];
        $systemAdmins  = [];

        $studentPermission = permissionChecker('student_view');
        $teacherPermission = permissionChecker('teacher_view');
        $parentPermission = permissionChecker('parents_view');
        $userPermission = permissionChecker('user_view');
        $systemadminPermission = permissionChecker('systemadmin_view');

        if($studentPermission){
            if(strpos($text, 'student') !== false){
                $text1 = str_replace("student", "",$text);
            }elseif(strpos($text, 'class') !== false){
                $text1 = str_replace("class", "",$text);
            }else{
                $text1 = $text;
            }
            $students = $this->student_m->searchStudents($text1,$limit,$page);
        }

        if($teacherPermission){
            if(strpos($text, 'teacher') !== false){
                $text1 = str_replace("teacher", "",$text);
            }else{
                $text1 = $text;
            }
            $teachers   = $this->teacher_m->searchTeachers($text1,$limit,$page);
        }

        if($parentPermission){
            if(strpos($text, 'parents') !== false){
                $text1 = str_replace("parents", "",$text);
               
            }else{
                $text1 = $text;
            }
            $parents    = $this->parents_m->searchParents($text1,$limit,$page);
        }

        if($userPermission){
            if(strpos($text, 'user') !== false){
                $text1 = str_replace("user", "",$text);
            }else{
                $text1 = $text;
            }
            $users    = $this->user_m->searchUsers($text1,$limit,$page);
        }

        if($systemadminPermission){
            if(strpos($text, 'admin') !== false){
                $text1 = str_replace("admin", "",$text);
            }else{
                $text1 = $text;
            }
            $systemAdmins   = $this->systemadmin_m->searchSystemAdmins($text1,$limit,$page);
        }
        
        $allArray = array_merge($students,$teachers ,$parents,$users,$systemAdmins);

        $result = [];
        $resultList = [];
        if(count($allArray) > 0){
            foreach($allArray as $array){
                $photoname = $array['photo'];
             if ($photoname != null) {
                 if (file_exists(FCPATH . 'uploads/images/' . $photoname)) {
                     $src = base_url('uploads/images/' . $photoname);
                 } else {
                     $src = base_url('uploads/images/default.png');
                 }
             } else {
                 $src = base_url('uploads/images/default.png');
             }
 
             $userTypeObj = $this->usertype_m->get_single_usertype(['usertypeID' => $array['usertypeID']]);
             $userType = $userTypeObj->usertype;

             $url = '';
             if($array['usertypeID'] == 1){
                $url = base_url().'systemadmin/view/'.$array['ID'];
                }elseif($array['usertypeID'] == 2){
                 $url = base_url().'teacher/view/'.$array['ID'];
             }elseif($array['usertypeID'] == 3){
                 $id = $array['cid'];
                 $url = base_url().'student/view/'.$array['ID'].'/'.$id;
             }elseif($array['usertypeID'] == 4){
                 $url = base_url().'parents/view/'.$array['ID'];
             }else{
                 $url = base_url().'user/view/'.$array['ID'];
             }
 
                $result[] = [
                     'id'  =>  $array['ID'],
                     'name' => $array['name'],
                     'icon' => $src,
                     'url'  => $url,
                     'usertype' => $userType,
                     'designation' => isset($array['designation'])?$array['designation']:'',
                     'registerNO'  => isset($array['registerNO'])?$array['registerNO']:'',
                     'class'  => isset($array['classes'])?$array['classes']:'',
                     'section'  => isset($array['section'])?$array['section']:'',
                     'roll'  => isset($array['roll'])?$array['roll']:''
                ];

                $resultList[$array['ID']] = [
                    'id'  =>  $array['ID'],
                    'name' => $array['name'],
                    'icon' => $src,
                    'url'  => $url,
                    'usertype' => $userType,
                    'designation' => isset($array['designation'])?$array['designation']:'',
                    'registerNO'  => isset($array['registerNO'])?$array['registerNO']:'',
                    'class'  => isset($array['classes'])?$array['classes']:'',
                    'section'  => isset($array['section'])?$array['section']:'',
                    'roll'  => isset($array['roll'])?$array['roll']:''
               ];
            }
        }

        return [$result,$resultList];

    }


    public function getUsersForExport($text){

        $userType = $this->session->userdata('usertypeID');

        $students      = [];
        $users         = [];
        $teachers      = [];
        $parents       = [];
        $systemAdmins  = [];

        $studentPermission = permissionChecker('student_view');
        $teacherPermission = permissionChecker('teacher_view');
        $parentPermission = permissionChecker('parents_view');
        $userPermission = permissionChecker('user_view');
        $systemadminPermission = permissionChecker('systemadmin_view');

        if($studentPermission){
            if(strpos($text, 'student') !== false){
                $text1 = str_replace("student", "",$text);
            }elseif(strpos($text, 'class') !== false){
                $text1 = str_replace("class", "",$text);
            }else{
                $text1 = $text;
            }
            $students = $this->student_m->searchStudentsExport($text1);
        }

        if($teacherPermission){
            if(strpos($text, 'teacher') !== false){
                $text1 = str_replace("teacher", "",$text);
            }else{
                $text1 = $text;
            }
            $teachers   = $this->teacher_m->searchTeachersExport($text1);
        }

        if($parentPermission){
            if(strpos($text, 'parents') !== false){
                $text1 = str_replace("parents", "",$text);
               
            }else{
                $text1 = $text;
            }
            $parents    = $this->parents_m->searchParentsExport($text1);
        }

        if($userPermission){
            if(strpos($text, 'user') !== false){
                $text1 = str_replace("user", "",$text);
            }else{
                $text1 = $text;
            }
            $users    = $this->user_m->searchUsersExport($text1);
        }

        if($systemadminPermission){
            if(strpos($text, 'admin') !== false){
                $text1 = str_replace("admin", "",$text);
            }else{
                $text1 = $text;
            }
            $systemAdmins   = $this->systemadmin_m->searchSystemAdminsExport($text1);
        }

        return [$students,$teachers,$parents,$users,$systemAdmins];

    }


    public function loadMoreResult(){

        $text = $_REQUEST['text'];
        $p = $_REQUEST['p'];
        list($result, $resultList )= $this->getUsers($text,7,$p);
        $this->data['results'] = $resultList;
        if($this->data['results']){
            echo $this->load->view('search/autoloadresult', $this->data, true);
            exit;
        }else{
            showBadRequest(null, "No data.");
        }
    }

    public function exportExcel(){

        $text = $_REQUEST['text'];
        list($students,$teachers,$parents,$users,$systemAdmins)= $this->getUsersForExport($text);
        $newArray = [];
        if($students){
            $newArray['students'] = $students;
        }
        if($teachers){
            $newArray['teachers'] = $teachers;
        }
        if($parents){
            $newArray['parents'] = $parents;
        }
        if($users){
            $newArray['users'] = $users;
        }
        if($systemAdmins){
            $newArray['systemadmins'] = $systemAdmins;
        }
       
        return $this->generateXML($newArray,$text);
    }
   
    public function exportPDF(){

        $text = $_REQUEST['text'];
        list($students,$teachers,$parents,$users,$systemAdmins)= $this->getUsersForExport($text);
        $this->data['students'] = $students;
        $this->data['teachers'] = $teachers;
        $this->data['parents'] = $parents;
        $this->data['users'] = $users;
        $this->data['systemadmins'] = $systemAdmins;
        $this->resultPDF('searchresult.css', $this->data, 'search/searchPDF');
        
    }

    public function resultPDF($stylesheet=NULL, $data=NULL, $viewpath= NULL, $mode = 'view', $pagesize = 'a4', $pagetype='portrait') {
		
        $designType = 'LTR';
		$this->data['panel_title'] = $this->lang->line('panel_title');
		$html = $this->load->view($viewpath, $this->data, true);

		$this->load->library('mhtml2pdf');

		$this->mhtml2pdf->folder('uploads/search/');
		$this->mhtml2pdf->filename('searchusers');
		$this->mhtml2pdf->paper($pagesize, $pagetype);
		$this->mhtml2pdf->html($html);

		if(!empty($stylesheet)) {
			$stylesheet = file_get_contents(base_url('assets/pdf/'.$designType.'/'.$stylesheet));
			return $this->mhtml2pdf->create($mode, $this->data['panel_title'], $stylesheet);
		} else { 
			return $this->mhtml2pdf->create($mode, $this->data['panel_title']);
		}
	}


    private function generateXML($data,$text) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $length = count($data);
        $i = 0;
        foreach($data as $key=>$value){

         if($i < $length){   

         $sheet = $spreadsheet->createSheet($i); //Setting index when creating

         if($key == 'students'){
            $sheet->setCellValue('A1', 'S.N');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'RegisterNO');
            $sheet->setCellValue('D1', 'Class');
            $sheet->setCellValue('E1', 'Section'); 
            $sheet->setCellValue('F1', 'Roll'); 
            $sheet->setCellValue('G1', 'Blood Group');
            $sheet->setCellValue('H1', 'Country');   
            $sheet->setCellValue('I1', 'DOB');  
            $sheet->setCellValue('J1', 'Sex');
            $sheet->setCellValue('K1', 'Email');
            $sheet->setCellValue('L1', 'Phone');
            $sheet->setCellValue('M1', 'Address');
            $rows = 2;
            $j = 1;
            foreach ($value as $val){
                $sheet->setCellValue('A' . $rows, $j);
                $sheet->setCellValue('B' . $rows, $val->name);
                $sheet->setCellValue('C' . $rows, $val->registerNO);
                $sheet->setCellValue('D' . $rows, $val->classes);
                $sheet->setCellValue('E' . $rows, $val->section);
                $sheet->setCellValue('F' . $rows, $val->roll);
                $sheet->setCellValue('G' . $rows, $val->bloodgroup);
                $sheet->setCellValue('H' . $rows, $val->country);
                $sheet->setCellValue('I' . $rows, $val->dob);
                $sheet->setCellValue('J' . $rows, $val->sex);
                $sheet->setCellValue('K' . $rows, $val->email);
                $sheet->setCellValue('L' . $rows, $val->phone);
                $sheet->setCellValue('M' . $rows, $val->address);
                $rows++;
                $j++;
            } 
         }

         if($key == 'teachers'){
            $sheet->setCellValue('A1', 'S.N');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Designation');
            $sheet->setCellValue('D1', 'DOB');  
            $sheet->setCellValue('E1', 'Sex');
            $sheet->setCellValue('F1', 'Email');
            $sheet->setCellValue('G1', 'Phone');
            $sheet->setCellValue('H1', 'Address');
            $rows = 2;
            $j = 1;
            foreach ($value as $val){
                $sheet->setCellValue('A' . $rows, $j);
                $sheet->setCellValue('B' . $rows, $val->name);
                $sheet->setCellValue('C' . $rows, $val->designation);
                $sheet->setCellValue('D' . $rows, $val->dob);
                $sheet->setCellValue('E' . $rows, $val->sex);
                $sheet->setCellValue('F' . $rows, $val->email);
                $sheet->setCellValue('G' . $rows, $val->phone);
                $sheet->setCellValue('H' . $rows, $val->address);
                $rows++;
                $j++;
            } 
         }

         if($key == 'parents'){
            $sheet->setCellValue('A1', 'S.N');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Phone');
            $sheet->setCellValue('E1', 'Address');
            $rows = 2;
            $j = 1;
            foreach ($value as $val){
                $sheet->setCellValue('A' . $rows, $j);
                $sheet->setCellValue('B' . $rows, $val->name);
                $sheet->setCellValue('C' . $rows, $val->email);
                $sheet->setCellValue('D' . $rows, $val->phone);
                $sheet->setCellValue('E' . $rows, $val->address);
                $rows++;
                $j++;
            } 
         }

         if($key == 'users'){
            $sheet->setCellValue('A1', 'S.N');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'DOB');  
            $sheet->setCellValue('D1', 'Sex');
            $sheet->setCellValue('E1', 'Email');
            $sheet->setCellValue('F1', 'Phone');
            $sheet->setCellValue('G1', 'Address');
            $rows = 2;
            $j = 1;
            foreach ($value as $val){
                $sheet->setCellValue('A' . $rows, $j);
                $sheet->setCellValue('B' . $rows, $val->name);
                $sheet->setCellValue('C' . $rows, $val->dob);
                $sheet->setCellValue('D' . $rows, $val->sex);
                $sheet->setCellValue('E' . $rows, $val->email);
                $sheet->setCellValue('F' . $rows, $val->phone);
                $sheet->setCellValue('G' . $rows, $val->address);
                $rows++;
                $j++;
            } 
         }

         if($key == 'systemadmins'){
            $sheet->setCellValue('A1', 'S.N');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'DOB');  
            $sheet->setCellValue('D1', 'Sex');
            $sheet->setCellValue('E1', 'Email');
            $sheet->setCellValue('F1', 'Phone');
            $sheet->setCellValue('G1', 'Address');
            $rows = 2;
            $j = 1;
            foreach ($value as $val){
                $sheet->setCellValue('A' . $rows, $j);
                $sheet->setCellValue('B' . $rows, $val->name);
                $sheet->setCellValue('C' . $rows, $val->dob);
                $sheet->setCellValue('D' . $rows, $val->sex);
                $sheet->setCellValue('E' . $rows, $val->email);
                $sheet->setCellValue('F' . $rows, $val->phone);
                $sheet->setCellValue('G' . $rows, $val->address);
                $rows++;
                $j++;
            } 
         }
         $sheet->setTitle($key);
         $i++;

        }

         $spreadsheet->setActiveSheetIndex(0);
         $writer = new Xlsx($spreadsheet);
         $filename = 'serachresult';
         header('Content-Type: application/vnd.ms-excel');
         header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
         header('Cache-Control: max-age=0');
         $writer->save('php://output');
			
    }   
	}

}
