<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courses_m extends MY_Model {

	protected $_table_name = 'courses';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Unit_m');
	}

	public function get_single_courses($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

    public function get_courses($id=NULL, $signal=false) 
	{
		$query = parent::get($id, $signal);
		return $query;
	}

	public function get_order_by_courses($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_join_course($id) 
	{
		$this->db->select('courses.*,classes.classes,subject.subject');
		$this->db->from('courses');
		$this->db->where('id', $id);
		$this->db->join('classes', 'courses.class_id = classes.classesID', 'LEFT');
		$this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
		$this->db->order_by('courses.id asc');
		$query = $this->db->get();
		return $query->row();
	}


	public function get_join_courses($classId = NULL, $subjectId = NULL,$limit='',$start='')
	{
		$this->db->select('courses.*, subject.subject, subject.photo');
		$this->db->from('courses');
		$this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
		$this->db->order_by('courses.id asc');
		if($classId) {
		    $this->db->where('class_id', $classId);
        }
        if($subjectId) {
            $this->db->where('subject_id', $subjectId);
        }
		if($limit)
            $this->db->limit($limit, $start);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_join_courses_subject($classId = NULL)
	{
		$this->db->select('courses.*, subject.type,subject.subject,subject.subject_code,subject.prerequisites');
		$this->db->from('courses');
		$this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
		$this->db->order_by('courses.id asc');
		if($classId) {
		    $this->db->where('class_id', $classId);
        }
		$this->db->where('published', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_join_courses_based_on_class_id($class_id) 
	{
		$this->db->select('courses.*,classes.classes,classes.classesID,subject.subject, subject.photo');
		$this->db->from('courses');
		$this->db->where('class_id', $class_id);
		$this->db->where('published', 1);
		$this->db->join('classes', 'courses.class_id = classes.classesID', 'LEFT');
		$this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
		$this->db->order_by('courses.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_join_courses_based_on_course_id($id) 
	{
		$this->db->select('courses.*,classes.classes,classes.classesID,subject.subject');
		$this->db->from('courses');
		$this->db->where('courses.id', $id);
		$this->db->where('courses.published', 1);
		$this->db->join('classes', 'courses.class_id = classes.classesID', 'LEFT');
		$this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
		$this->db->order_by('courses.id asc');
		$query = $this->db->get();
		return $query->row();
	}


	public function get_all_join_courses_based_on_course_id($id) 
	{
		$this->db->select('courses.*,classes.classes,classes.classesID,subject.subject');
		$this->db->from('courses');
		$this->db->where('courses.id', $id);
		$this->db->join('classes', 'courses.class_id = classes.classesID', 'LEFT');
		$this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
		$this->db->order_by('courses.id asc');
		$query = $this->db->get();
		return $query->row();
	}


	public function get_course_unit($id) 
	{
		$this->db->select('course_unit_chapter.*, zzz_4_units.unit_name,zzz_1_chapters.chapter_name');
		$this->db->from('course_unit_chapter');
		$this->db->join('zzz_4_units', 'course_unit_chapter.unit_id = zzz_4_units.id', 'LEFT');
		$this->db->join('zzz_1_chapters', 'course_unit_chapter.chapter_id = zzz_1_chapters.id', 'LEFT');
		$this->db->where('course_unit_chapter.id', $id);
		$this->db->order_by('course_unit_chapter.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_course_unit_by_course($id) 
	{
		$this->db->select('zzz_4_units.*,course_unit.id as course_unitid');
		$this->db->from('zzz_4_units');
		$this->db->join('courses', 'courses.subject_id = zzz_4_units.subject_id', 'LEFT');
		$this->db->join('course_unit', 'courses.subject_id = zzz_4_units.subject_id', 'LEFT');
		$this->db->where('courses.id', $id);
		$this->db->order_by('courses.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_course_chapters($course_id)
	{
		$this->db->select('zzz_1_chapters.id');
		$this->db->from('zzz_1_chapters');
		$this->db->join('courses', 'courses.subject_id = zzz_1_chapters.subject_id', 'LEFT');
		$this->db->where('courses.id', $course_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_published_course_unit_by_course($id) 
	{
		$this->db->select('zzz_4_units.*,course_unit.id as course_unitid');
		$this->db->from('zzz_4_units');
		$this->db->where('zzz_4_units.published', 1);
		$this->db->join('courses', 'courses.subject_id = zzz_4_units.subject_id', 'LEFT');
		$this->db->join('course_unit', 'courses.subject_id = zzz_4_units.subject_id', 'LEFT');
		$this->db->where('courses.id', $id);
		$this->db->order_by('courses.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_course_unit_by_unit($id) 
	{
		$this->db->select('zzz_4_units.*,course_unit.id as course_unitid');
		$this->db->from('zzz_4_units');
		$this->db->join('courses', 'courses.subject_id = zzz_4_units.subject_id', 'LEFT');
		$this->db->join('course_unit', 'courses.subject_id = zzz_4_units.subject_id', 'LEFT');
		$this->db->where('zzz_4_units.id', $id);
		$this->db->order_by('zzz_4_units.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_course_unit_chapter($course_unit_id){
		$this->db->select('zzz_1_chapters.*');
		$this->db->from('zzz_1_chapters');
		$this->db->join('zzz_4_units', 'zzz_4_units.id = zzz_1_chapters.unit_id', 'LEFT');
		$this->db->where('zzz_4_units.id', $course_unit_id);
		$this->db->order_by('zzz_4_units.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_published_course_unit_chapter($course_unit_id){
		$this->db->select('zzz_1_chapters.*');
		$this->db->from('zzz_1_chapters');
		$this->db->where('zzz_1_chapters.published', 1);
		$this->db->join('zzz_4_units', 'zzz_4_units.id = zzz_1_chapters.unit_id', 'LEFT');
		$this->db->where('zzz_4_units.id', $course_unit_id);
		$this->db->order_by('zzz_4_units.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_course_unit_chapter_resource($coursechapter_id){
		$this->db->select('coursechapter_resource.*');
		$this->db->from('coursechapter_resource');
		$this->db->where('coursechapter_resource.coursechapter_id', $coursechapter_id);
		$this->db->order_by('coursechapter_resource.id asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function getUnits($course_id){
        $this->db->select('courses.*');
        $this->db->from('courses');
        $this->db->where('courses.id', $course_id);
        $query = $this->db->get();
        $subject_id = $query->row_object()->subject_id;
        $unit = $this->Unit_m->get_unit_from_subject_id($subject_id);
        return $unit;
	}

	public function get_coursecontent($course_id){
        $this->db->select('coursechapter_resource.*');
        $this->db->from('coursechapter_resource');
        $this->db->where('coursechapter_resource.coursechapter_id', $course_id);
        $query = $this->db->get();
      
        return $query->result();
	}

	public function insert_courses($array) 
	{
		$error = parent::insert($array);
		return $error;
	}

	function insert_unit($array) {
		$this->db->insert('course_unit', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	function insert_chapter($array) {
		$this->db->insert('course_unit_chapter', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	function insert_resource($array) {
		$this->db->insert('coursechapter_resource', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	function insert_quizzes($array) {
		$this->db->insert('coursechapter_quiz', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	function insert_attachment($array) {
		$this->db->insert('coursefiles_resources', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	function insert_link($array) {
		$this->db->insert('courselink', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	function insert_quiz_result($array) {
		$this->db->insert('coursequiz_result', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	public function insert_questions($data,$quiz_id, $quizzes)
    {
		
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('coursechapter_quiz', $data);
            
			$message      = "Updated record On coursechapter_quiz id ".$data['id'];
			$action       = "Update";
			$record_id    = $data['id'];
			
			//======================Code End==============================

			$this->db->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
        } else {
            $this->db->insert('coursechapter_quiz', $data);
            $id = $this->db->insert_id();
							
			$message      = "Inserted record On coursechapter_quiz id ".$id;
			$action       = "Insert";
			$record_id    = $id;
			
			//======================Code End==============================

			$this->db->trans_complete(); # Completing transaction
			/*Optional*/

			if ($this->db->trans_status() === false) {
				# Something went wrong.
				$this->db->trans_rollback();
				return false;

			} else {
				//return $return_value;
			}
        }
        
        $quizzes_array = array();
        $i = 0;
        foreach ($quizzes as $key => $value) {
            
            $vehicle_array = array(
                'coursechapter_id'   => $data,
                'quiz_id' => $quiz_id,
                'question_id' => $value
            );

            $quizzes_array[] = $vehicle_array;
            $i++;
        }
       
        $this->db->insert_batch('coursechapterquiz_question', $quizzes_array);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
	}
	
	public function delete_questions($chapter_id, $quiz_id, $question_id)
    {
		$this->db->where('coursechapter_id', $chapter_id);
		$this->db->where('quiz_id', $quiz_id);
		$this->db->where('question_id', $question_id);
		$this->db->delete('coursechapterquiz_question');
	}

	public function get_content($id){
        $this->db->select('coursechapter_resource.*');
        $this->db->from('coursechapter_resource');
        $this->db->where('coursechapter_resource.coursechapter_id', $id);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_content_by_course($id){
        $this->db->select('coursechapter_resource.*');
        $this->db->from('coursechapter_resource');
        $this->db->where('coursechapter_resource.course_id', $id);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_contents($id){
        $this->db->select('coursechapter_resource.*');
        $this->db->from('coursechapter_resource');
        $this->db->where('coursechapter_resource.coursechapter_id', $id);
        $this->db->where('coursechapter_resource.published', 1);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_all_contents($id){
        $this->db->select('coursechapter_resource.*');
        $this->db->from('coursechapter_resource');
        $this->db->where('coursechapter_resource.coursechapter_id', $id);
        // $this->db->where('coursechapter_resource.published', 1);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_attachment($id){
        $this->db->select('coursefiles_resources.*');
        $this->db->from('coursefiles_resources');
        $this->db->where('coursefiles_resources.coursechapter_id', $id);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_published_attachment($id){
        $this->db->select('coursefiles_resources.*');
        $this->db->from('coursefiles_resources');
		$this->db->where('coursefiles_resources.coursechapter_id', $id);
        $this->db->where('coursefiles_resources.published', 1);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_link($id){
        $this->db->select('courselink.*');
        $this->db->from('courselink');
        $this->db->where('courselink.coursechapter_id', $id);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_published_link($id){
        $this->db->select('courselink.*');
        $this->db->from('courselink');
		$this->db->where('courselink.coursechapter_id', $id);
        $this->db->where('courselink.published', 1);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_quizzes($id){
        $this->db->select('coursechapter_quiz.*');
        $this->db->from('coursechapter_quiz');
        $this->db->where('coursechapter_quiz.coursechapter_id', $id);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_published_quizzes($id){
        $this->db->select('coursechapter_quiz.*');
        $this->db->from('coursechapter_quiz');
        $this->db->where('coursechapter_quiz.coursechapter_id', $id);
        $this->db->where('coursechapter_quiz.published', 1);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_quiz($id){
        $this->db->select('coursechapter_quiz.*');
        $this->db->from('coursechapter_quiz');
        $this->db->where('coursechapter_quiz.id', $id);
        $query = $this->db->get();
        return $query->row();
	}

	public function get_published_quiz($id){
        $this->db->select('coursechapter_quiz.*');
        $this->db->from('coursechapter_quiz');
        $this->db->where('coursechapter_quiz.id', $id);
        $this->db->where('coursechapter_quiz.published', 1);
        $query = $this->db->get();
        return $query->row();
	}

	function get_order_by_quiz_question($id) {
		$this->db->select('coursechapterquiz_question.quiz_id, coursechapterquiz_question.question_id, question_bank.*');
        $this->db->from('coursechapterquiz_question');
        $this->db->join('question_bank', 'coursechapterquiz_question.question_id = question_bank.questionBankID', 'LEFT');
        $this->db->where('coursechapterquiz_question.quiz_id', $id);
		$this->db->order_by('coursechapterquiz_question.order_no asc');
        $query = $this->db->get();
        return $query->result();
	}

	
	public function get_quiz_result($user_id, $quiz_id){
        $this->db->select('coursequiz_result.*');
        $this->db->from('coursequiz_result');
        $this->db->where('coursequiz_result.user_id', $user_id);
        $this->db->where('coursequiz_result.quiz_id', $quiz_id);
        $query = $this->db->get();
        return $query->result();
	}

	public function get_quiz_report($user_id, $quiz_id){
        $this->db->select('coursequiz_result.*');
        $this->db->from('coursequiz_result');
        $this->db->where('coursequiz_result.user_id', $user_id);
		$this->db->where('coursequiz_result.quiz_id', $quiz_id);
		$this->db->order_by('coursequiz_result.total_percentage desc');		
        $query = $this->db->get();
        return $query->row();
	}

	public function update_courses($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function update_attachment($data, $id = NULL) 
	{
		return $this->db->update('coursefiles_resources',$data,array('id' => $id));

	}

    public function get_join_courses_based_on_teacher_id($classId, $teacherId)
    {
        $this->db->select('courses.*,classes.classes,subject.subjectID, subject.subject, subject.photo');
        $this->db->from('courses');
        $this->db->join('classes', 'courses.class_id = classes.classesID', 'LEFT');
        $this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
        $this->db->join('subjectteacher st', 'st.subjectID = subject.subjectID', 'LEFT');
        $this->db->where('st.teacherID', $teacherId);
        $this->db->where('courses.class_id', $classId);
        $this->db->order_by('courses.id asc');
        $query = $this->db->get();
        return $query->result();
	}

    public function updateContentOrder($id, $position) {
        $this->db->set('order', $position);
        $this->db->where('id', $id);
        $this->db->update('coursechapter_resource');
    }

    public function updateLinkOrder($id, $position) {
        $this->db->set('order', $position);
        $this->db->where('id', $id);
        $this->db->update('courselink');
    }

    public function updateQuizOrder($id, $position) {
        $this->db->set('order', $position);
        $this->db->where('id', $id);
        $this->db->update('coursechapter_quiz');
    }

	public function updateQuizQuestionOrder($id, $position,$quizzID) {
		$this->db->set('order_no', $position);
        $this->db->where('question_id', $id);
		$this->db->where('quiz_id', $quizzID);
        $this->db->update('coursechapterquiz_question');
    }

    public function updateAttachmentOrder($id, $position) {
        $this->db->set('order', $position);
        $this->db->where('id', $id);
        $this->db->update('coursefiles_resources');
    }

	public function get_quizzes_by_course($course_id = '', $usertypeid = '')
	{
		$this->db->select('cw.* , c.id As coursechapter_id, c.unit, c.unit_id,c.chapter_name');
		$this->db->from('coursechapter_quiz cw');
		$this->db->join('zzz_1_chapters c', 'c.id = cw.coursechapter_id', 'LEFT');
		// $this->db->join(' courses co', 'co.subject_id = c.subject_id', 'LEFT');
		// $this->db->where('co.id', $course_id);
		$this->db->or_where('cw.course_id', $course_id);
		if($usertypeid == 3 || $usertypeid == 4 ){
			$this->db->where('cw.published', 1);
		}
		$query = $this->db->get()->result();
		return $query;
		
		
	}

}	
