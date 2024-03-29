<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacherclasses_m extends CI_Model {
	protected $_table_name = 'classes';
	protected $_primary_key = 'classesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classes_numeric asc";

	public function teacherClass($type = NULL) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$teacherID = $this->session->userdata('loginuserID');

		if(isset($type) && $type == 'not_primary') {
			$this->db->select('classesID')->from('classes')->where(array('teacherID' => $teacherID))->not_like('classes', 'kg')->not_like('classes', 'nursery')->order_by('classesID');
		} else if (isset($type) && $type == 'primary') {
			$this->db->select('classesID')->from('classes')->where(array('teacherID' => $teacherID))->like('classes', 'kg')->or_like('classes', 'nursery')->order_by('classesID');
		} else {
			$this->db->select('classesID')->from('classes')->where(array('teacherID' => $teacherID))->order_by('classesID');
		}
		$classQuery = $this->db->get();
		$classResult = $classQuery->result();

		if(isset($type) && $type == 'not_primary') {
			$this->db->select('section.classesID')->from('section');
			$this->db->join('classes', 'classes.classesID = section.classesID', 'LEFT');
			$this->db->where(array('section.teacherID' => $teacherID));
			$this->db->not_like('classes.classes', 'kg')->not_like('classes.classes', 'nursery')->order_by('section.classesID');
		} else if (isset($type) && $type == 'primary') {
			$this->db->select('section.classesID')->from('section');
			$this->db->join('classes', 'classes.classesID = section.classesID', 'LEFT');
			$this->db->where(array('section.teacherID' => $teacherID))->like('classes.classes', 'kg')->or_like('classes.classes', 'nursery')->order_by('section.classesID');
		} else {
			$this->db->select('classesID')->from('section')->where(array('teacherID' => $teacherID))->order_by('classesID');
		}
		$sectionQuery = $this->db->get();
		$sectionResult = $sectionQuery->result();

		$this->db->select('classesID')->from('subjectteacher')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$subjectTeacherClassQuery = $this->db->get();
		$subjectTeacherClassResult = $subjectTeacherClassQuery->result();

		$this->db->select('classesID')->from('subjectteacher')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$subjectTeacherClassQuery = $this->db->get();
		$subjectTeacherClassResult = $subjectTeacherClassQuery->result();

        $this->db->select('classesID')->from('routine')->where(array('teacherID' => $teacherID, 'schoolyearID' => $schoolyearID))->order_by('classesID');
		$weekends = $this->data['siteinfos']->weekends;
		if($weekends != '') {
		    $weekendArray = explode(',', $weekends);
		    if(customCompute($weekendArray)) {
		        foreach ($weekendArray as $week) {
                    $this->db->where(['day !=' => $week]);
                }
            }
        }

        $routineQuery = $this->db->get();
        $routineResult = $routineQuery->result();

		$classMerged = (object) array_merge((array) $classResult , (array) $routineResult,(array) $subjectTeacherClassResult,(array) $sectionResult);
		$classes = array_unique(pluck($classMerged, 'classesID', 'classesID','classesID'));
		ksort($classes);
		return $classes;
	}

	public function get_teacher_class($classesID = NULL, $single = FALSE) {
		$classArray = $this->teacherClass();

		if(customCompute($classArray)) {
			if($classesID) {
				if(in_array($classesID, $classArray)) {
					$this->db->where_in($this->_primary_key, $classArray[$classesID]);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);

					if ($classesID != NULL) {
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
				$this->db->where_in($this->_primary_key, $classArray);
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if ($classesID != NULL) {
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

	public function get_single_teacher_class($array = NULL) {
		$classArray = $this->teacherClass();

		if(customCompute($classArray)) {
			if(is_array($array)) {
				if(isset($array['classesID'])) {
					if(in_array($array['classesID'], $classArray)) {
						$this->db->where_in($this->_primary_key, $classArray[$array['classesID']]);
						unset($array['classesID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						if(customCompute($query)) {
							if(in_array($query[0]->classesID, $classArray)) {
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
					$this->db->where_in($this->_primary_key, $classArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->where_in($this->_primary_key, $classArray);
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

	public function get_order_by_teacher_class($array = NULL, $type = NULL) {
		$classArray = $this->teacherClass($type);
		
		if(customCompute($classArray)) {
			if(is_array($array)) {
				if(isset($array['classesID'])) {
					if(in_array($array['classesID'], $classArray)) {
						$this->db->where_in($this->_primary_key, $classArray[$array['classesID']]);
						unset($array['classesID']);
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
					$this->db->where_in($this->_primary_key, $classArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->where_in($this->_primary_key, $classArray);
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

	public function get_teacher_with_class() {
		$classArray = $this->teacherClass();
		if(customCompute($classArray)) {
			$this->db->join('teacher', 'classes.teacherID = teacher.teacherID', 'LEFT');
			$this->db->where_in('classes.'.$this->_primary_key, $classArray);
			$this->db->order_by($this->_order_by); 
			$query = $this->db->get($this->_table_name);
			return $query->result();
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}
}