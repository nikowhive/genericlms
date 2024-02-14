<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Teacherstudent_m.php';
require_once 'Studentparentstudent_m.php';

class student_m extends MY_Model {

	protected $_table_name = 'student';
	protected $_primary_key = 'student.studentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "roll asc";

	function __construct() {
		parent::__construct();
	}

	public function get_single_stud($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function getStudentByID($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_single_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->row();
	}

	function get_class($id=NULL) {
		$class = new Classes_m;
	    return $class->get_classes($id);
	}

	function get_classes() {
	    $class = new Classes_m;
	    return $class->get_order_by_classes();
	}


	public function general_get_student($array=NULL, $signal=FALSE) {
		$array = $this->makeArrayWithTableName($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_students($class_id){
	    $this->db->select('student.*');
		$this->db->from('student');
		$this->db->where('student.active', 1);
		$this->db->where('classesID', $class_id);
		$this->db->order_by('student.studentID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_student_by_register_number($array){
	    $this->db->select('student.*');
		$this->db->from('student');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_student_by_roll_number($array){
	    $this->db->select('student.*');
		$this->db->from('student');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->row();
	}
    
    public function get_allstudents($array = []){
	    $this->db->select('student.name,student.email,student.photo, student.sectionID, section.sectionID, section.section, classes.classes as category1,\'student\' as category2,\'3\' as usertypeID,student.studentID as id,parents.parentsID');
		$this->db->from('student');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->join('parents', 'student.parentID = parents.parentsID', 'LEFT');
		$this->db->where('student.active', 1);
		//$this->db->where('classesID', $class_id);
		if(customCompute($array)){
			$this->db->where($array);
		}
		$this->db->order_by('student.studentID asc');
		
		$query = $this->db->get();
		return $query->result();
	}

	public function get_allstudentsarray(){
	    $this->db->select('student.name,student.studentID,student.photo,classes.classesID,classes.classes,section.section');
		$this->db->from('student');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->where('student.active', 1);
		//$this->db->where('classesID', $class_id);
		$this->db->order_by('student.studentID asc');
		$this->db->where('student.schoolyearID', $this->session->userdata('defaultschoolyearID'));
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_allstudentsjson(){
		$students = $this->get_allstudentsarray();
		$i = 0;
        $array = [];
		foreach($students as $student){
			$array[$i]['label'] = '<img src="'.base_url().'uploads/images/'.$student['photo'].'" width="70" />&nbsp;&nbsp;&nbsp;'.$student['name'].'('.$student['classes'].'/'.$student['section'].')';
			$array[$i]['the_link'] = base_url().'student/view/'.$student['studentID'].'/'.$student['classesID'];
			$i++;
		}
		return json_encode($array);
	}

	public function get_allstudentsparents(){
		$this->db->select('student.name,parents.email,parents.photo, student.sectionID, section.sectionID, section.section, classes.classes as category1,\'parent\' as category2,\'4\' as usertypeID,parents.parentsID as id');
		$this->db->from('student');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->where('student.active', 1);
		//$this->db->where('classesID', $class_id);
		$this->db->order_by('student.studentID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_parents_with_child(){
		$this->db->select('parents.name, parents.email,parents.photo, student.name as child_name, student.sectionID, section.sectionID, section.section, classes.classes as category1,\'parent\' as category2,\'4\' as usertypeID,parents.parentsID as id');
		$this->db->from('student');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->where('student.active', 1);
		//$this->db->where('classesID', $class_id);
		$this->db->order_by('student.studentID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_teachers(){
		$this->db->select('teacher.name,teacher.email,teacher.photo,\'employee\' as category2,\'teacher\' as category1,\'2\' as usertypeID,teacher.teacherID as id');
		$this->db->from('teacher');
		$this->db->order_by('teacher.teacherID asc');
		$query = $this->db->get();
		return $query->result();
	}
    
    public function get_admin(){
		$this->db->select('systemadmin.name,systemadmin.email,systemadmin.photo,\'employee\' as category2,\'admin\' as category1,\'1\' as usertypeID,systemadmin.systemadminID as id');
		$this->db->from('systemadmin');
		//$this->db->where('usertypeID', '1');
		$this->db->order_by('systemadmin.systemadminID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_accountant(){
		$this->db->select('user.name,user.email,user.photo,\'employee\' as category2,\'accountant\' as category1,\'5\' as usertypeID,user.userID as id');
		$this->db->from('user');
		$this->db->where('usertypeID', '5');
		$this->db->order_by('user.userID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_librarian(){
		$this->db->select('user.name,user.email,user.photo,\'employee\' as category2,\'librarian\' as category1,\'6\' as usertypeID,user.userID as id');
		$this->db->from('user');
		$this->db->where('usertypeID', '6');
		$this->db->order_by('user.userID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_receptionist(){
		$this->db->select('user.name,user.email,user.photo,\'employee\' as category2,\'receptionist\' as category1,\'7\' as usertypeID,user.userID as id');
		$this->db->from('user');
		$this->db->where('usertypeID', '7');
		$this->db->order_by('user.userID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_moderator(){
		$this->db->select('user.name,user.email,user.photo,\'employee\' as category2,\'moderator\' as category1,\'8\' as usertypeID,user.userID as id');
		$this->db->from('user');
		$this->db->where('usertypeID', '8');
		$this->db->order_by('user.userID asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_student_ids_from_class_id($class_id) {
		$table_name = $this->_table_name;
		$query = $this->db->select('studentID')
				->from($table_name)
				->where_in('classesID', $class_id);
		$array = $query->get()->result_array();
		$arr = array_column($array, "studentID");
		return $arr;
	}


	public function get_students_from_section_id($section_id) {
		$table_name = $this->_table_name;
		$query = $this->db->select('student.*, parents.parentsID, parents.name as parent_name, parents.phone as parent_phone')
				->from($table_name)
				->join('parents', 'parents.parentsID = student.parentID', 'LEFT')
				->where_in('sectionID', $section_id)
				->where('student.active',1);
		$array = $query->get()->result();
		return $array;
	}

	public function general_get_order_by_student($array=NULL) {
		$teacherstudent = new Teacherstudent_m;
		$array = $teacherstudent->prefixLoad($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_student($array) {
		$teacherstudent = new Teacherstudent_m;
		$array = $teacherstudent->prefixLoad($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_where_in_student($array, $key = NULL) {
		$query = parent::get_where_in($array, $key);
		return $query;
	}

	public function get_student($id=NULL, $single=FALSE) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_teacher_student($id, $single);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_studentparent_student($id, $single);
		} else {
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get($id, $single);
			return $query;
		}
	}

	public function get_single_student($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_single_teacher_student($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_single_studentparent_student($array);
		} else {
			$teacherstudent = new Teacherstudent_m;
			$array = $teacherstudent->prefixLoad($array);
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get_single($array);
			return $query;
		}
	}

	public function get_individual_student($studentID) {
		$table_name = $this->_table_name;
		$query = $this->db->select('*')
				->from($table_name)
				->where('studentID', $studentID);
		return $query->get()->row();;
	}

	public function get_order_by_student($array=[]) {
        $usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_order_by_teacher_student($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_order_by_studentparent_student($array);
		} else {
			$teacherstudent = new Teacherstudent_m;
			$array = $teacherstudent->prefixLoad($array);
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get_order_by($array);
			return $query;
		}
	}

	public function get_select_student($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'studentID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function insert_student($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function insert_parent($array) {
		$this->db->insert('parent', $array);
		return TRUE;
	}

	public function update_student($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_student_classes($data, $array = NULL) {
		$this->db->set($data);
		$this->db->where($array);
		$this->db->update($this->_table_name);
	}

	public function delete_student($id){
		parent::delete($id);
	}

	public function delete_parent($id){
		$this->db->delete('parent', array('studentID' => $id));
	}

	public function hash($string) {
		return parent::hash($string);
	}

	public function profileUpdate($table, $data, $username) {
		$this->db->update($table, $data, "username = '".$username."'");
		return TRUE;
	}

	public function profileRelationUpdate($table, $data, $studentID, $schoolyearID) {
		$this->db->update($table, $data, "srstudentID = '".$studentID."' AND srschoolyearID = '".$schoolyearID."'");
		return TRUE;
	}

	/* Start For Promotion */
	public function get_order_by_student_year($classesID) {
		$query = $this->db->query("SELECT * FROM student WHERE year = (SELECT MIN(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->result();
	}

	public function get_order_by_student_single_year($classesID) {
		$query = $this->db->query("SELECT year FROM student WHERE year = (SELECT MIN(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->row();
	}

	public function get_order_by_student_single_max_year($classesID) {
		$query = $this->db->query("SELECT year FROM student WHERE year = (SELECT MAX(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->row();
	}
	/* End For Promotion */


	/* Start For Report */
	public function get_order_by_student_with_section($classesID, $schoolyearID, $sectionID=NULL) {
		$this->db->select('student.*,parents.email as pemail');
		$this->db->from('student');
		$this->db->join('classes', 'student.classesID = classes.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->where('student.classesID', $classesID);
		//$this->db->where('student.classesID', $sectionID);
		$this->db->where('student.schoolyearID', $schoolyearID);
		if($sectionID != NULL) {
			$this->db->where('student.sectionID', $sectionID);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_order_by_student_with_section_for_attendance($classesID, $schoolyearID, $sectionID=NULL) {
		$this->db->select('student.*,parents.email as pemail');
		$this->db->from('student');
		$this->db->join('classes', 'student.classesID = classes.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->where('student.classesID', $classesID);
		$this->db->where('student.active', 1);
		//$this->db->where('student.classesID', $sectionID);
		$this->db->where('student.schoolyearID', $schoolyearID);
		if($sectionID != NULL) {
			$this->db->where('student.sectionID', $sectionID);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_order_by_student_with_section1($classesID, $schoolyearID, $sectionID=NULL) {
		$this->db->select('student.*,parents.email as pemail');
		$this->db->from('student');
		$this->db->join('classes', 'student.classesID = classes.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->where('student.classesID', $classesID);
		$this->db->where('student.active', 1);
		//$this->db->where('student.classesID', $sectionID);
		$this->db->where('student.schoolyearID', $schoolyearID);
		if($sectionID != '') {
			$this->db->where_in('student.sectionID', $sectionID);
		}
		$query = $this->db->get();
		return $query->result();
	}

	/* End For Report */

	public function get_max_student() {
		$query = $this->db->query("SELECT * FROM $this->_table_name WHERE studentID = (SELECT MAX(studentID) FROM $this->_table_name)");
		return $query->row();
	}

	public function get_student_count_by_status($sectionID) {
		$query = $this->db->select('count(*) As total_student, sum(case when active = "1" then 1 else 0 end ) As active_student, sum(case when active = "0" then 1 else 0 end ) As inactive_student',FALSE)
          ->from( $this->_table_name)
		  ->where('sectionID',$sectionID)
           ->get();

         return $query->row();
	}

	public function get_student_number($sectionID = NULL){
		$this->db->select('studentID');
		$this->db->from('student');
		$this->db->where('student.sectionID', $sectionID);
		return $this->db->count_all_results();
	}

	public function get_student_active_students($sectionID = NULL){
		$this->db->select('studentID');
		$this->db->from('student');
		$this->db->where('student.sectionID', $sectionID);
		return $this->db->count_all_results();
	}

	public function get_student_numberclass($classesID = NULL){
		$this->db->select('studentID');
		$this->db->from('student');
		$this->db->where('student.active', 1);
		$this->db->where('student.classesID', $classesID);
		return $this->db->count_all_results();
	}

	public function get_yearwise_total_students($schoolyearID){
	    $this->db->select('student.*');
		$this->db->from('student');
		$this->db->where('schoolyearID', $schoolyearID);
		$this->db->where('active', 1);
		return $query = $this->db->count_all_results();
	}

	public function get_yearwiseandsectionwise_total_students($arrays = []){
	    $this->db->select('studentID');
		$this->db->from('student');
		$this->db->where('active', 1);
		if(customCompute($arrays)) {
            $this->db->where($arrays);
        }
		return $this->db->count_all_results();
	}

	public function searchStudents($text,$limit,$start){

		$text = trim($text);

		$this->db->select('student.name,student.studentID AS ID,student.photo,student.registerNO,student.roll,
		student.usertypeID,student.classesID AS cid,classes.classes,section.section');
		$this->db->from('student');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');
		$this->db->where('student.active', 1);		
		if($text != ''){
			$this->db->like('student.name', $text);
			$this->db->or_like('student.registerNO', $text);
			$this->db->or_like('classes.classes', $text);
		}
		$this->db->where('student.schoolyearID', $this->session->userdata('defaultschoolyearID'));
		if($limit)
            $this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result_array();

	}

    public function searchStudentsExport($text,$limit = '',$start= ''){

		$text = trim($text);

		$this->db->select('student.studentID,
				student.name,
				student.registerNO,
				student.roll,
				student.bloodgroup,
				student.country,
				student.dob,
				student.sex,
				student.email,
				student.photo,
				student.phone,
				student.address,
				classes.classes,
				section.section,
				student.usertypeID
				');
		$this->db->from('student');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');		
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');
		$this->db->where('student.active', 1);		
		if($text != ''){
			$this->db->like('student.name', $text);
			$this->db->or_like('student.registerNO', $text);
			$this->db->or_like('classes.classes', $text);
		}
		$this->db->where('student.schoolyearID', $this->session->userdata('defaultschoolyearID'));
		
		if($limit){
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();

	}

	public function studentsExport($filters = []){

		$this->db->select('student.studentID,
				student.name,
				student.registerNO,
				student.roll,
				student.bloodgroup,
				student.country,
				student.dob,
				student.sex,
				student.email,
				student.photo,
				student.phone,
				student.address,
				classes.classes,
				section.section,
				student.usertypeID
				');
		$this->db->from('student');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');		
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');

		if(isset($filters['classesID']) && $filters['classesID'] != ''){
           $this->db->where('student.classesID',$filters['classesID']);
		}
		if(isset($filters['sectionID']) && $filters['sectionID'] != ''){
			$this->db->where('student.sectionID',$filters['sectionID']);
		 }
		if(isset($filters['schoolyearID']) && $filters['schoolyearID'] != ''){
			$this->db->where('student.schoolyearID',$filters['schoolyearID']);
		}
		$this->db->where('student.active', 1);		
		$query = $this->db->get();
		return $query->result();

	}

	public function get_parent_by_id($parentID = NULL){
		$this->db->select('name');
		$this->db->from('parents');
		$this->db->where('parents.parentsID', $parentID);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_student_by_id($studentID = NULL){
		$this->db->select('name');
		$this->db->from('student');
		$this->db->where('student.active', 1);
		$this->db->where('student.studentID', $studentID);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_student_feed($array=NULL, $opt=NULL) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->where('active', 1);
		if($array){
			$this->db->where($array);
		}
		$query = $this->db->get();
		if($opt){
			return $qry = $query->num_rows();
		}else{
			return $query->result();
		}
	}
	public function getAllActiveStudents($array){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

		$this->db->select('student.studentID AS ID,student.usertypeID');
		$this->db->from('student');
		$this->db->where($array);
		$this->db->where("(studentID != '$userID' OR usertypeID != '$userTypeID')");
		$this->db->where('student.schoolyearID', $this->session->userdata('defaultschoolyearID'));
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getAllStudentsContact($filters = [],$limit = '', $start = ''){

		$this->db->select('student.studentID as id,student.name,student.phone,student.address,
		student.email,student.photo,section.section,classes.classes,
		student.usertypeID');
		$this->db->from('student');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');
		$this->db->join('subjectteacher', 'subjectteacher.classesID = classes.classesID', 'LEFT');
		$this->db->join('teacher', 'teacher.teacherID = subjectteacher.teacherID OR teacher.teacherID = classes.teacherID', 'LEFT');
		
		if(isset($filters['schoolyearID'])){
			$this->db->where('student.schoolyearID',$filters['schoolyearID']);
		}
		if(isset($filters['classesID'])){
			$this->db->where('student.classesID',$filters['classesID']);
		}
		if(isset($filters['sectionID'])){
			$this->db->where('student.sectionID',$filters['sectionID']);
		}
		if(isset($filters['studentID'])){
			$this->db->where('student.studentID !=' .$filters['studentID']);
		}

		if(isset($filters['teacherID'])){
			$this->db->where('teacher.teacherID',$filters['teacherID']);
		}

		if(isset($filters['parentID'])){
			$this->db->where('student.parentID',$filters['parentID']);
		}

		if ($limit)
            $this->db->limit($limit, $start);

		$this->db->where('student.active', 1);
		$this->db->group_by('student.studentID');
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getAllActiveStudentsDetails($classesIds = [],$name = ''){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

		$this->db->select('section.section,classes.classes,student.studentID as ID,student.usertypeID,student.name,student.roll,student.registerNO,parents.name as parentName,parents.phone as parentPhone');
		$this->db->from('student');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');
		$this->db->where('student.active',1);
		$this->db->where("(student.studentID != '$userID' OR student.usertypeID != '$userTypeID')");
		$this->db->where('student.schoolyearID', $this->session->userdata('defaultschoolyearID'));
		$this->db->where_in('student.classesID',$classesIds);
		
		if($name){
			$this->db->like('student.name',$name);
		}
		$this->db->order_by('student.classesID');
		$query = $this->db->get();
		$result = $query->result_array();
		$newResults = [];
		if(customCompute($result)){
			foreach($result as $r){
				$newResults['Student-'.create_slug($r['classes']).'-'.create_slug($r['section'])][] = $r;
			}
		}
		return $newResults;

	}

	public function get_student_count_by_classes($schoolyearID) {
		$query = $this->db->query("SELECT classes, classes.classesID, count(student.classesID) as no  FROM `classes` left Join student on  student.classesID=classes.classesID WHERE student.schoolyearID = '$schoolyearID' AND student.active = 1 group by classes.classesID");
		return $query->result();
	}

	public function get_user_count_by_usertype() {
		$query = $this->db->query("SELECT usertype.usertype, usertype.usertypeID, count(user.usertypeID) as no  FROM `usertype` left Join user on  user.usertypeID=usertype.usertypeID AND user.active = 1 group by usertype.usertypeID");
		return $query->result_array();
	}

	public function getAllActiveParentsDetails($classesIds,$name = ''){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
		
		$this->db->select('student.name as studentName,classes.classes,section.section,parents.parentsID AS ID,parents.usertypeID,parents.name,parents.email,parents.phone');
		$this->db->from('student');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');
		$this->db->where('student.active',1);
		$this->db->where("(student.studentID != '$userID' OR student.usertypeID != '$userTypeID')");
		$this->db->where('student.schoolyearID', $this->session->userdata('defaultschoolyearID'));
		$this->db->where_in('student.classesID',$classesIds);
	
		if($name){
			$this->db->like('parents.name',$name);
		}
		$this->db->where('student.parentID !=', '');
		$this->db->order_by('student.classesID');
		$query = $this->db->get();
		$result = $query->result_array();
		$newResults = [];
		if(customCompute($result)){
			foreach($result as $r){
				$newResults['Parent-'.create_slug($r['classes']).'-'.create_slug($r['section'])][] = $r;
			}
		}
		return $newResults;

	}

	public function student_login_details($studentID){

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->db->from('studentrelation');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		   $this->db->where('studentrelation.srschoolyearID =', $schoolyearID);
		   $this->db->where('student.studentID !=', NULL);
		   $this->db->where('studentrelation.srstudentID', $studentID);
		   $this->db->order_by('srroll asc');
		$studentrelationQuery = $this->db->get();
		return $studentrelationResult = $studentrelationQuery->row();

	}

}