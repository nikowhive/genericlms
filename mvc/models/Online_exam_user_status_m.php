<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_exam_user_status_m extends MY_Model {

    protected $_table_name = 'online_exam_user_status';
    protected $_primary_key = 'onlineExamUserStatus';
    protected $_primary_filter = 'intval';
    protected $_order_by = "totalObtainedMark desc";

    function __construct() {
        parent::__construct();
    }

    function get_online_exam_user_status($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_online_exam_user_status($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_online_exam_user_status($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_online_exam_user_status($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_online_exam_user_status($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_online_exam_user_status($id){
        parent::delete($id);
    }

    public function get_join_online_exam_user_status($array, $schoolyearID) {
        $this->db->select('online_exam_user_status.*,online_exam.schoolYearID,online_exam.subjectID');
        $this->db->from('online_exam_user_status');
        $this->db->join('online_exam', 'online_exam.onlineExamID = online_exam_user_status.onlineExamID');
        $this->db->join('studentextend', 'studentextend.studentID = online_exam_user_status.userID', 'LEFT');
        $this->db->join('studentgroup', 'studentextend.studentgroupID = studentgroup.studentgroupID', 'LEFT');
        $this->db->join('classes', 'classes.classesID = online_exam_user_status.classesID', 'LEFT');
        $this->db->join('student', 'student.studentID = online_exam_user_status.userID', 'LEFT');
        if(isset($array['onlineexamID'])) {
            $this->db->where('online_exam_user_status.onlineexamID', $array['onlineexamID']);
        }
        if(isset($array['classesID'])) {
            $this->db->where('online_exam_user_status.classesID', $array['classesID']);
        }
        if(isset($array['sectionID'])) {
            $this->db->where('online_exam_user_status.sectionID', $array['sectionID']);
        }
        if(isset($array['studentgroupID'])) {
            $this->db->where('studentextend.studentgroupID',$array['studentgroupID']);
        }
        if(isset($array['userID'])) {
            $this->db->where('online_exam_user_status.userID', $array['userID']);
        }
        if(isset($array['statusID'])) {
            $this->db->where('online_exam_user_status.statusID', $array['statusID']);
        }
        // if(customCompute($array)) {
        //     foreach ($array as $key => $value) {
        //         $this->db->where('online_exam_user_status.'.$key,$value);
        //     }
        // }
        $this->db->where('online_exam.schoolYearID', $schoolyearID);
        $query = $this->db->get();
        return $query->result();
    }


    public function get_all_exam_details($array)
    {
        $this->db->select('online_exam_user_status.*, studentextend.studentID, studentextend.studentgroupID, studentgroup.group, student.studentID, student.name, student.roll, student.registerNO, classes.classesID, classes.classes');
        $this->db->from('online_exam_user_status');
        $this->db->join('studentextend', 'studentextend.studentID = online_exam_user_status.userID', 'LEFT');
        $this->db->join('studentgroup', 'studentextend.studentgroupID = studentgroup.studentgroupID', 'LEFT');
        $this->db->join('classes', 'classes.classesID = online_exam_user_status.classesID', 'LEFT');
        $this->db->join('student', 'student.studentID = online_exam_user_status.userID', 'LEFT');
        if(isset($array['onlineexamID'])) {
            $this->db->where('online_exam_user_status.onlineexamID', $array['onlineexamID']);
        }
        if(isset($array['classesID'])) {
            $this->db->where('online_exam_user_status.classesID', $array['classesID']);
        }
        if(isset($array['studentgroupID'])) {
            $this->db->where('studentextend.studentgroupID',$array['studentgroupID']);
        }
        if(isset($array['sectionID'])) {
            $this->db->where('online_exam_user_status.sectionID', $array['sectionID']);
        }
        if(isset($array['userID'])) {
            $this->db->where('online_exam_user_status.userID', $array['userID']);
        }
        if(isset($array['statusID'])) {
            $this->db->where('online_exam_user_status.statusID', $array['statusID']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    //new added
    public function get_online_exam_user_ans($id)
    {   $this->db->select('*');
        $this->db->from('online_exam_user_answer_option as AO');
        $this->db->join('question_bank as QB', 'AO.questionID = QB.questionBankID', 'LEFT');
        $this->db->join('question_option as QO', 'AO.optionID = QO.optionID', 'left outer');
        // $this->db->group_by('AO.questionID'); 
        $this->db->where('AO.onlineExamUserAnswerID',$id);
        $query = $this->db->get();
        if(!empty($query))
        return $query->result();
       else
        return '';
    }

   // public function get_online_exam_user_ans($onlineExamID)
   // {
   //     $this->db->select('BQ.question,BQ.questionBankID,BQ.typeNumber,BQ.mark,BQ.type_id');
   //     $this->db->from('question_bank as BQ');
   //     $this->db->join('online_exam_question as OQ','BQ.questionBankID = OQ.questionID','JOIN');
   //     $this->db->where('OQ.onlineExamID',$onlineExamID);
   //     $query = $this->db->get();
   //     return $query->result();
   // }
//new added
public function get_options($qsid,$uniqueid)
   {
       $this->db->select('sum(OO.onlineExamUserAnswerOptionID) as aqq, OO.onlineExamUserAnswerOptionID, OO.full_mark,OO.correct_ans,OO.text,OO.obtained_mark,QA.text as qatest');
       $this->db->from('online_exam_user_answer_option as OO');
       $this->db->join('question_answer as QA', 'OO.questionID = QA.questionID', 'LEFT');
       $this->db->where('OO.questionID',$qsid);
       $this->db->where('OO.onlineExamUserAnswerID',$uniqueid);
       $this->db->group_by('OO.onlineExamUserAnswerOptionID');
       $query = $this->db->get();
       return $query->result();
   }
//new added
   public function optdetails($uniqid)
   {
       $this->db->distinct();
       $this->db->select('questionID');
       $this->db->select('full_mark');
       $this->db->select('obtained_mark');
       $this->db->from('online_exam_user_answer_option');
       $this->db->where('onlineExamUserAnswerID',$uniqid);
       $query = $this->db->get();
       return $query->result();
   }
//new added
   public function getopts($typeid,$unqid)
   {
       $this->db->select('obtained_mark');
       $this->db->select('correct_ans');
       $this->db->from('online_exam_user_answer_option');
       $this->db->where('typeID',$typeid);
       $this->db->where('onlineExamUserAnswerID',$unqid);
       $query = $this->db->get();
       $result = $query->result();
       if($query->num_rows() > 1)
       {
           foreach($result as $resultval)
           {
               //echo $resultval->obtained_mark;
               if($resultval->obtained_mark != $resultval->correct_ans)
               {
                   $updatedata = ['obtained_mark' => 0];
                   $this->db->where('typeID', $typeid);
                   $this->db->where('onlineExamUserAnswerID', $unqid);
                   $this->db->update('online_exam_user_answer_option', $updatedata);
                   
               }
           }
       }
   }
   //new added
public function totscore($uniqid,$typeid)
   {
       $this->db->select('ans_status');
       $this->db->from('online_exam_user_answer_option');
       $this->db->where('onlineExamUserAnswerID',$uniqid);
       $this->db->where('typeID!=',$typeid);
       $this->db->where('ans_status',1);
       $this->db->where('attend',0);
       $query = $this->db->get();
       //echo $this->db->last_query();
       $totcount = $query->num_rows();
       $optcount = $this->totalscore($uniqid,3);
       $returnval = $totcount+$optcount; 
       return $returnval;
   }
   //new added
   public function totalscore($uniqid,$typeid)
   {
       $score = 0;
       $this->db->select('ans_status');
       $this->db->from('online_exam_user_answer_option');
       $this->db->where('onlineExamUserAnswerID',$uniqid);
       $this->db->where('typeID',$typeid);
       $this->db->where('attend',0);
       $query = $this->db->get();
       $result = $query->result();
       $counttot = $query->num_rows();
       if(!empty($result))
       {
           foreach($result as $rval)
           {
               $score += $rval->ans_status;  
           }
       }
      if($counttot == $score)
      {
          return 1;
      }
      else
      {
          return 0;
      }
   }
//new added
   public function checksubjective($examID)
   {
       $this->db->select('OS.onlineExamUserStatus');
       $this->db->from('online_exam_user_status as OS');
       $this->db->join('online_exam_user_answer_option as OO', 'OS.onlineExamUserAnswerID = OO.onlineExamUserAnswerID');
       $this->db->where('OS.onlineExamID',$examID);
       $this->db->where('OO.typeID',4);
       $query = $this->db->get();
       $totcount = $query->num_rows();
       return $totcount; 
   }
//new added
 public function getgpa($perc)
 {
     if($perc > 0)
     {
         $this->db->select('*');
         $this->db->from('grade');
         $this->db->where('"'.$perc.'" BETWEEN gradefrom AND gradeupto', '',false);
         $query = $this->db->get();
         $row =  $query->row();
         return $row;
    }
     else
     {
       return '';
     }
 }
//new added
 public function getmarks($userid,$classid,$eid)
   {
       // $query = "select DISTINCT EAO.examID, concat(round(((EAO.obtained_mark / EAO.full_mark) * 100 ),2), '%') as permark,sum(EAO.obtained_mark) as obtainemark,ES.totalMark,ES.duration,ES.userID,ES.totalMark,S.subject,S.passmark,S.finalmark
       //           from online_exam_user_answer_option as EAO 
       //           join online_exam_user_status as ES ON EAO.onlineExamUserAnswerID = ES.onlineExamUserAnswerID
       //           join online_exam as OE ON ES.onlineExamID = OE.onlineExamID
       //           join subject as S ON OE.sectionID = S.subjectID
       //           where EAO.user_id = ".$userid." and EAO.examID = ".$tid." group by EAO.onlineExamUserAnswerID";
       $query = "select ES.totalPercentage,ES.totalObtainedMark,ES.totalMark,ES.duration,ES.userID,ES.totalMark,S.subject,S.passmark,S.finalmark
                 from online_exam_user_status as ES
                 join online_exam as OE ON ES.onlineExamID = OE.onlineExamID
                 join subject as S ON OE.subjectID = S.subjectID
                 where ES.userID = ".$userid." and ES.classesID = ".$classid." and ES.onlineExamID IN (".$eid.")";
       $result = $this->db->query($query);
       //echo $this->db->last_query();
       return $result->result();
   }
 //new added
   public function getexamid($eid)
   {
       $query = "SELECT GROUP_CONCAT(terminal_id SEPARATOR ', ') tid FROM marksheet_details where marksheet_id =".$eid." GROUP BY marksheet_id";
       $result = $this->db->query($query);
       return $result->row();
   }

   //new added
   public function totalcurrectans($eid)
   {
       $query = "SELECT sum(ans_status) as totalcurrectans FROM online_exam_user_answer_option where onlineExamUserAnswerID =".$eid." GROUP BY onlineExamUserAnswerID";
       $result = $this->db->query($query);
       return $result->row()->totalcurrectans;
   }
}
