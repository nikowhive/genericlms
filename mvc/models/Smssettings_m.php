<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class smssettings_m extends MY_Model {

	function get_order_by_clickatell() {
		$query = $this->db->get_where('smssettings', array('types' => 'clickatell'));
		return $query->result();
	}

	function update_clickatell($array) {
		$this->db->update_batch('smssettings', $array, 'field_names'); 
	}

	function get_order_by_twilio() {
		$query = $this->db->get_where('smssettings', array('types' => 'twilio'));
		return $query->result();
	}

	function update_twilio($array) {
		$this->db->update_batch('smssettings', $array, 'field_names'); 
	}

	function get_order_by_bulk() {
		$query = $this->db->get_where('smssettings', array('types' => 'bulk'));
		return $query->result();
	}

	function update_bulk($array) {
		$this->db->update_batch('smssettings', $array, 'field_names'); 
	}

    function get_order_by_msg91() {
        $query = $this->db->get_where('smssettings', array('types' => 'msg91'));
        return $query->result();
    }

    function update_msg91($array) {
		$this->db->update_batch('smssettings', $array, 'field_names');
	}

	function get_order_by_sparrow() {
        $query = $this->db->get_where('smssettings', array('types' => 'sparrow'));
        return $query->result();
    }

    function update_sparrow($array) {
		$this->db->update_batch('smssettings', $array, 'field_names');
	}

	public function get_single_sparrow_sms_credits(){
		$query = $this->db->select("field_values")->get_where('smssettings', array('types' => 'sparrow','field_names' => 'sparrow_credits'));
        $data = $query->row();
		if($data){
			return $data->field_values;
		}else{
			return 0;
		}
	}

	public function update_sparrow_credits($credits){

		$this->db->where('field_names','sparrow_credits');
		$this->db->update('smssettings',['field_values' => $credits]);

	}      

}