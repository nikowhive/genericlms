<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teachersection_m extends CI_Model {
	protected $_table_name = 'section';
	protected $_primary_key = 'sectionID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "sectionID asc";

	public function teacherSection() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$teacherID = $this->session->userdata('loginuserID');

		$this->db->from('classes')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$classQuery = $this->db->get();
		$classResult = $classQuery->result();

		$this->db->from('section')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$sectionQuery = $this->db->get();
		$sectionResult = $sectionQuery->result();
		
		// $this->db->from('usertype')->where(array('usertypeID' => $this->session->userdata('usertypeID')));
		// $usertypeQuery = $this->db->get();
		// $usertypeResult = $usertypeQuery->row();

		// $sectionSection = [];
		// if(customCompute($usertypeResult)) {
		// 	$this->db->from('section')->where(array('create_userID' => $teacherID, 'create_usertype' => $usertypeResult->usertype))->order_by('sectionID');
		// 	$sectionQuery = $this->db->get();
		// 	$sectionSection = $sectionQuery->result();
			
		// }


		$sectionArray = [];
		$classSection = [];
		if(customCompute($classResult)) {
			$classPluck = pluck($classResult, 'classesID');
			if(customCompute($classPluck)) {
				$this->db->where_in('classesID', $classPluck);
				$classSection = $this->db->get($this->_table_name);
				$classSection = $classSection->result();
			}
		}

		$routineSection = [];
		$this->db->from('routine')->where(array('teacherID' => $teacherID, 'schoolyearID' => $schoolyearID))->order_by('classesID');
		$routineQuery = $this->db->get();
		$routineSection = $routineQuery->result();

		$subjectTeacherClassResult = [];
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

		$sectionMerged = (object) array_merge((array) $classSection , (array) $routineSection,(array) $subjectTeacherClassSection,(array) $sectionResult);

		// if(customCompute($sectionSection)) {
		// 	$sectionMerged = (object) array_merge((array) $sectionMerged , (array) $sectionSection);
		// }

		$section = array_unique(pluck($sectionMerged, 'sectionID', 'sectionID','sectionID'));
		ksort($section);
		return $section;
	}

	public function get_teacher_section($sectionID = NULL, $single = FALSE) {
		$sectionArray = $this->teacherSection();
		if(customCompute($sectionArray)) {
			if($sectionID) {
				if(in_array($sectionID, $sectionArray)) {
					$this->db->where_in($this->_primary_key, $sectionArray[$sectionID]);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);

					if ($sectionID != NULL) {
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
				$this->db->where_in($this->_primary_key, $sectionArray);
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if ($sectionID != NULL) {
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

	public function get_single_teacher_section($array = NULL) {
		$sectionArray = $this->teacherSection();

		if(customCompute($sectionArray)) {
			if(is_array($array)) {
				if(isset($array['sectionID'])) {
					if(in_array($array['sectionID'], $sectionArray)) {
						$this->db->where_in($this->_primary_key, $sectionArray[$array['sectionID']]);
						unset($array['sectionID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						if(customCompute($query)) {
							if(in_array($query[0]->sectionID, $sectionArray)) {
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
					$this->db->where_in($this->_primary_key, $sectionArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->where_in($this->_primary_key, $sectionArray);
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

	public function get_order_by_teacher_section($array = NULL) {
		$sectionArray = $this->teacherSection();
		
		if(customCompute($sectionArray)) {
			if(is_array($array)) {
				if(isset($array['sectionID'])) {
					if(in_array($array['sectionID'], $sectionArray)) {
						$this->db->where_in($this->_primary_key, $sectionArray[$array['sectionID']]);
						unset($array['sectionID']);
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
					$this->db->where_in($this->_primary_key, $sectionArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->where_in($this->_primary_key, $sectionArray);
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

	public function get_teacher_with_section($id) {
		$sectionArray = $this->teacherSection();
		if(customCompute($sectionArray)) {
			$this->db->select('*');
			$this->db->from('section');
			$this->db->join('teacher', 'section.teacherID = teacher.teacherID', 'LEFT');
			$this->db->where_in('section.'.$this->_primary_key, $sectionArray);
			$this->db->where('section.classesID', $id);
			$query = $this->db->get();
			return $query->result();
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_sectionteacher($id){
		$this->db->select('*');
		$this->db->from('teacher');
		$this->db->join('section','section.teacherID = teacher.teacherID','RIGHT');
		$this->db->where('section.sectionID',$id);
		$query = $this->db->get();
		return $query->row();
	}
}