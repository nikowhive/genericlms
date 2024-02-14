<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_m extends MY_Model {

	function get_order_by_logs($array) {
		$query = $this->db->get_where('student_logged_in_logout_records', $array);
		return $query->result();
	}

    function get_single_log($array) {
            $this->db->select("*");
			$this->db->from('student_logged_in_logout_records');
            $this->db->where($array); 
			$this->db->order_by('id', 'desc');
			return $this->db->get()->row();
	}

    function insert_log($array) {
        $insert = $this->db->insert('student_logged_in_logout_records', $array);
        return $insert ? true:false;
	}

	function update_log($data, $id = NULL) {
            $this->db->where('id', $id);
	        $upate = $this->db->update('student_logged_in_logout_records', $data);
            return $upate ? true:false;
	}


	function get_order_by_course_logs($array,$limit = '', $start= '') {

		$this->db->select("student_courses_logs.*,student.name");
		$this->db->from('student_courses_logs');
		$this->db->join('student', 'student.studentID = student_courses_logs.studentID', 'LEFT');


		if(isset($array['schoolyearID']) &&  $array['schoolyearID'] != ''){
			$this->db->where('student_courses_logs.schoolyearID',$array['schoolyearID']); 
		}

		if(isset($array['studentID']) &&  $array['studentID'] != ''){
			$this->db->where('student_courses_logs.studentID',$array['studentID']); 
		}
		if(isset($array['eventID']) &&  $array['eventID'] != ''){
			if($array['eventID'] == 1) {
				$this->db->where('student_courses_logs.event','login-logout'); 
			}else{
				$this->db->where_not_in('student_courses_logs.event','login-logout'); 
			}
		}

		if(isset($array['startDate']) &&  $array['startDate'] != '' && isset($array['endDate']) &&  $array['endDate'] != ''){
			$this->db->where('student_courses_logs.start_datetime >=', $array['startDate'].' 00:00:00');
			$this->db->where('student_courses_logs.start_datetime <=', $array['endDate'].' 23:59:59');
		}

		if ($limit)
            $this->db->limit($limit, $start);
		
		$this->db->order_by('id', 'asc');
		return $this->db->get()->result();
		
	}


	function get_single_course_log($array) {
		$this->db->select("*");
		$this->db->from('student_courses_logs');
		$this->db->where($array); 
		$this->db->order_by('id', 'desc');
		return $this->db->get()->row();
    }   

    function insert_course_log($array) {
        $insert = $this->db->insert('student_courses_logs', $array);
        return $insert ? true:false;
	}

	function update_course_log($data, $id = NULL) {
		         $this->db->where('id', $id);
		$upate = $this->db->update('student_courses_logs', $data);
        return $upate ? true:false;
	}

	public function delete_log($array)
	{
        $this->db->where($array);
        $this->db->delete('student_courses_logs');
	}

	public function last_record($studentID)
	{ 
		$results = $this->db->select('id')->from('student_courses_logs')->where('studentID', $studentID)->where('event','chapter content page')->limit(2)->order_by('id','DESC')->get()->result();
		if(count($results) > 0){
			foreach($results as $result){
                 $this->delete_log(['id' => $result->id]);
			}
		}
	} 

	public function getSum($filters = [],$limit = '',$offset = '')
	{
		$schoolyearID = $filters['schoolyearID'];
		$startDate = $filters['startDate'].' 00:00:00';
		$endDate = $filters['endDate'].' 23:59:59';

		if($limit){
			$condition = "LIMIT $limit OFFSET $offset";
		}else{
			$condition = '';
		}
		
		if(isset($filters['eventID']) && $filters['eventID'] == 1 ){
			$sql = "SELECT scl.studentID,student.name,scl.event, sum(scl.second_spent) as second_spent,classes.classes,student.photo FROM student_courses_logs AS scl
			INNER JOIN student ON scl.studentID=student.studentID
			INNER JOIN classes ON student.classesID=classes.classesID
			WHERE (
					scl.schoolyearID = $schoolyearID AND
					scl.event = 'login-logout' AND
					scl.start_datetime >= '$startDate' AND
					scl.end_datetime <= '$endDate'
				  ) 
			GROUP BY scl.studentID,scl.event $condition";
		}elseif(isset($filters['eventID']) && $filters['eventID'] == 2 ){
		    $sql = "SELECT scl.studentID,student.name,scl.event, sum(scl.second_spent) as second_spent,classes.classes,student.photo FROM student_courses_logs AS scl
			        INNER JOIN student ON scl.studentID=student.studentID
					INNER JOIN classes ON student.classesID=classes.classesID
			        WHERE (
							scl.schoolyearID = $schoolyearID AND
							scl.event != 'login-logout' AND
							scl.start_datetime >= '$startDate' AND
							scl.end_datetime <= '$endDate'
						  ) 
					GROUP BY scl.studentID,scl.event $condition";
		    
		}else{
			$sql = "SELECT scl.studentID,student.name,scl.event, sum(scl.second_spent) as second_spent,classes.classes,student.photo FROM student_courses_logs AS scl
			        INNER JOIN student ON scl.studentID=student.studentID
					INNER JOIN classes ON student.classesID=classes.classesID
			        WHERE (
							scl.schoolyearID = $schoolyearID AND
							scl.start_datetime >= '$startDate' AND
							scl.end_datetime <= '$endDate'
						  ) 
					GROUP BY scl.studentID,scl.event $condition";
		}
		
		$query = $this->db->query($sql);
		$results = $query->result();
		$data = [];
		if(customCompute($results)){
            foreach($results as $result){
				$data[$result->studentID][] = $result;
			}
		}

		return $data;
		
	}

}