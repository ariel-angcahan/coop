<?php 

class Mdl_index extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function last_registered(){
		$this->db->limit(5);
		$this->db->select("CONCAT(first_name,' ', middle_name,' ',last_name) as name, date_created");
		$this->db->from('person_info');
		$this->db->order_by('id', 'desc');
		return $this->db->get()->result();
	}
}	