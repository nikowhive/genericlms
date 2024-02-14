<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Liveclass_m extends MY_Model
{

    protected $_table_name     = 'liveclass';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_liveclass($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_liveclass($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_liveclass($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_liveclass($array)
    {
        parent::insert($array);
        return true;
    }

    public function insert_batch_liveclass($array)
    {
        $id = parent::insert_batch($array);
        return $id;
    }

    public function update_liveclass($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_liveclass($id)
    {
        parent::delete($id);
    }

    public function get_liveclass_with_condition1($schoolYearId,$searchValue = '')
    {

        $classes = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');

        $sections     = [];
        $usertypeID   = $this->session->userdata("usertypeID");
        $loginuserID  = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ($usertypeID == 3) {
            $student                       = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID), false);
            $sections[$student->sectionID] = $student->sectionID;
            $subjects = pluck($this->subject_m->get_order_by_subject(['type' => 1]), 'subjectID', 'subjectID');
            if ((int) $student->sroptionalsubjectID) {
                $subjects[$student->sroptionalsubjectID] = $student->sroptionalsubjectID;
            }
        } else {
            $sections = pluck($this->section_m->get_section(), 'sectionID', 'sectionID');
            $subjects = pluck($this->subject_m->get_subject(), 'subjectID', 'subjectID');
        }

        $this->db->select('liveclass.*,classes.classes as classname,section.section as sectionname,subject.subject as subjectname');
        $this->db->from($this->_table_name);

        if (customCompute($classes) || customCompute($sections) || customCompute($subjects)) {
            $this->db->or_group_start();
            if (customCompute($classes)) {
                $this->db->group_start();
                foreach ($classes as $classes) {
                    $this->db->or_where('classes_id', $classes);
                }
                $this->db->or_where('classes_id', 0);
                $this->db->group_end();
            }

            if (customCompute($sections)) {
                $this->db->group_start();
                foreach ($sections as $section) {
                    $this->db->or_where('section_id', $section);
                }
                $this->db->or_where('section_id', 0);
                $this->db->group_end();
            }

            if (customCompute($subjects)) {
                $this->db->group_start();
                foreach ($subjects as $subject) {
                    $this->db->or_where('subject_id', $subject);
                }
                $this->db->or_where('subject_id', 0);
                $this->db->group_end();
            }
            $this->db->group_end();
        }

        $this->db->join('classes', 'classes.classesID = liveclass.classes_id', 'LEFT');
        $this->db->join('section', 'section.sectionID = liveclass.section_id', 'LEFT');
        $this->db->join('subject', 'subject.subjectID = liveclass.subject_id', 'LEFT');
        $this->db->where(['school_year_id' => $schoolYearId]);
        $this->db->where(['date >=' => date('Y-m-d H:i:s', strtotime('-30 days', strtotime(date('Y-m-d H:i:s'))))]);
        if($searchValue){
            $this->db->like('liveclass.title',$searchValue);
        }
        $this->db->order_by('date','ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_liveclass_with_condition($schoolYearId,$searchValue = '')
    {
        $classes = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
        if(!customCompute($classes )){
            return [];
        }
        $loginuserID  = $this->session->userdata('loginuserID');
        $this->db->select('liveclass.*,classes.classes as classname,section.section as sectionname,subject.subject as subjectname');
        $this->db->from($this->_table_name);
        $this->db->join('classes', 'classes.classesID = liveclass.classes_id', 'LEFT');
        $this->db->join('section', 'section.sectionID = liveclass.section_id', 'LEFT');
        $this->db->join('subject', 'subject.subjectID = liveclass.subject_id', 'LEFT');
        $this->db->join('subjectteacher', 'subjectteacher.subjectID = subject.subjectID', 'LEFT');
        $this->db->where(['school_year_id' => $schoolYearId]);
        $this->db->where("(classes.teacherID = '$loginuserID' OR section.teacherID = '$loginuserID' OR subjectteacher.teacherID = '$loginuserID')");
        $this->db->where(['date >=' => date('Y-m-d H:i:s', strtotime('-30 days', strtotime(date('Y-m-d H:i:s'))))]);
        if($searchValue){
            $this->db->like('liveclass.title',$searchValue);
        }
        $this->db->order_by('date','DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_todays_liveclass($schoolYearId = '')
    {
        
        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d 23:59:59');
        $this->db->select('liveclass.*,classes.classes as classname,section.section as sectionname,subject.subject as subjectname');
        $this->db->from($this->_table_name);
        $this->db->join('classes', 'classes.classesID = liveclass.classes_id', 'LEFT');
        $this->db->join('section', 'section.sectionID = liveclass.section_id', 'LEFT');
        $this->db->join('subject', 'subject.subjectID = liveclass.subject_id', 'LEFT');
        $this->db->join('subjectteacher', 'subjectteacher.subjectID = subject.subjectID', 'LEFT');
        if($schoolYearId){
            $this->db->where(['school_year_id' => $schoolYearId]);
        }
        $this->db->where('added_to_job',0);
        $this->db->where('date >=',$from);
        $this->db->where('date <=',$to);
        $this->db->order_by('date','DESC');
        $this->db->group_by('liveclass.id');
        $query = $this->db->get();
        return $query->result();
    }

}