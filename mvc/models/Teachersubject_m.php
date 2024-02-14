<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teachersubject_m extends CI_Model {
	protected $_table_name = 'subject';
	protected $_primary_key = 'subjectID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	public function teacherSubject() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$teacherID = $this->session->userdata('loginuserID');

		$this->db->from('classes')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$classQuery = $this->db->get();
		$classResult = $classQuery->result();

		$classSectionResult = [];
		if(customCompute($classResult)) {
			$classPluck = pluck($classResult, 'classesID');
			if(customCompute($classPluck)) {
				$this->db->where_in('classesID', $classPluck);
				$classSection = $this->db->get($this->_table_name);
				$classSectionResult = $classSection->result();
			}
		}

		$this->db->from('section')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$classQuery1 = $this->db->get();
		$classResult1 = $classQuery1->result();

		$classSectionResult1 = [];
		if(customCompute($classResult1)) {
			$classPluck1 = pluck($classResult1, 'classesID');
			if(customCompute($classPluck1)) {
				$this->db->where_in('classesID', $classPluck1);
				$classSection1 = $this->db->get($this->_table_name);
				$classSectionResult1 = $classSection1->result();
			}
		}


		$this->db->from('subjectteacher')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$subjectTeacherClassQuery = $this->db->get();
		$subjectTeacherClassResult = $subjectTeacherClassQuery->result();

		$subjectTeacherClassSection = [];
		if(customCompute($subjectTeacherClassResult)) {
			$classPluck1 = pluck($subjectTeacherClassResult, 'classesID');
			if(customCompute($classPluck1)) {
				$this->db->where_in('classesID', $classPluck1);
				$subjectTeacherClassSection = $this->db->get($this->_table_name);
				$subjectTeacherClassSection = $subjectTeacherClassSection->result();
			}
		}

		$this->db->from('routine')->where(array('teacherID' => $teacherID, 'schoolyearID' => $schoolyearID))->order_by($this->_order_by);
		$routineQuery = $this->db->get();
		$routineResult = $routineQuery->result();

		$subjectMerged = (object) array_merge((array) $classSectionResult , (array) $routineResult, (array) $subjectTeacherClassSection,(array) $classSectionResult1);

		$subject = array_unique(pluck($subjectMerged, 'subjectID', 'subjectID','subjectID','subjectID'));
		ksort($subject);
		return $subject;
	}

	public function get_teacher_subject($subjectID = NULL, $single = FALSE) {
		$subjectArray = $this->teacherSubject();

		if(customCompute($subjectArray)) {
			if($subjectID) {
				if(in_array($subjectID, $subjectArray)) {
					$this->db->where_in($this->_primary_key, $subjectArray[$subjectID]);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);

					if($subjectID != NULL) {
						return $query->row();
					} elseif($single) {
						return $query->row();
					} else {
						return $query->result();
					}
				} else {
					$this->db->where(array($this->_primary_key => 0));
					$query = $this->db->get($this->_table_name);
					return $query->result();
				}
			} else {
				$this->db->where_in($this->_primary_key, $subjectArray);
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if ($subjectID != NULL) {
					return $query->row();
				} elseif($single) {
					return $query->row();
				} else {
					return $query->result();
				}
			}
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_single_teacher_subject($array = NULL) {
		$subjectArray = $this->teacherSubject();

		if(customCompute($subjectArray)) {
			if(is_array($array)) {
				if(isset($array['subjectID'])) {
					if(in_array($array['subjectID'], $subjectArray)) {
						$this->db->where_in($this->_primary_key, $subjectArray[$array['subjectID']]);
						unset($array['subjectID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						if(customCompute($query)) {
							if(in_array($query[0]->subjectID, $subjectArray)) {
								return $query[0];
							} else {
								$this->db->where(array($this->_primary_key => 0));
								$query = $this->db->get($this->_table_name);
								return $query->result();
							}
						} else {
							$this->db->where(array($this->_primary_key => 0));
							$query = $this->db->get($this->_table_name);
							return $query->result();
						}
					} else {
						$this->db->where(array($this->_primary_key => 0));
						$query = $this->db->get($this->_table_name);
						return $query->result();
					}
				} else {
					$this->db->where_in($this->_primary_key, $subjectArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->where_in($this->_primary_key, $subjectArray);
				$this->db->order_by($this->_order_by);
				$query = $this->db->get($this->_table_name);
				return $query->result();
			}
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_order_by_teacher_subject($array = NULL) {
		$subjectArray = $this->teacherSubject();
		
		if(customCompute($subjectArray)) {
			if(is_array($array)) {
				if(isset($array['subjectID'])) {
					if(in_array($array['subjectID'], $subjectArray)) {
						$this->db->where_in($this->_primary_key, $subjectArray[$array['subjectID']]);
						unset($array['subjectID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						return $query;
					} else {
						$this->db->where(array($this->_primary_key => 0));
						$query = $this->db->get($this->_table_name);
						return $query->result();
					}
				} else {
					$this->db->where_in($this->_primary_key, $subjectArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->where_in($this->_primary_key, $subjectArray);
				$this->db->order_by($this->_order_by);
				$query = $this->db->get($this->_table_name);
				return $query->result();
			}
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_subject_with_class($id) {
		$subjectArray = $this->teacherSubject();
		if(customCompute($subjectArray)) {
			$this->db->select('subject.*, classes.classesID, classes.classes, classes.classes_numeric, classes.studentmaxID, classes.note');
			$this->db->join('classes', 'classes.classesID = subject.classesID', 'LEFT');
			$this->db->where_in('subject.'.$this->_primary_key, $subjectArray);
			$this->db->where('subject.classesID', $id);
			$this->db->order_by('subject.'.$this->_order_by);
			$query = $this->db->get($this->_table_name);
			return $query->result();
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_subject_by_teacher(){
	    $this->db->select('subject');
	    $this->db->from('subject');
	    $this->db->join('subjectteacher','subject.subjectID = subjectteacher.subjectID','RIGHT');
	    $this->db->where(array('subjectteacher.teacherID'=>$this->session->userdata('loginuserID')));
	    $query = $this->db->get();
	    return $query->row();
	}
}