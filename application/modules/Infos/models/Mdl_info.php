<?php 

class Mdl_info extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function get_info($id){
		$response['success'] = false;
		$this->db->select("pi.id, date_created, last_name, first_name, middle_name, email, mobile_no, mam.address as market, (select stall_no from stall_mst where id = pi.stall_no_id) as stall_location, stall_description, mm.type as membership_type, subscription_amount, pmm.mode as payment_mode, payment_per_mode");
		$this->db->from("person_info_tmp as pi");
		$this->db->join("market_addr_mst as mam", "pi.market_addr_id = mam.id");
		$this->db->join("membership_mst as mm", "pi.membership_type_id = mm.id");
		$this->db->join("payment_mode_mst as pmm", "pi.payment_mode_id = pmm.id");
		$this->db->where("pi.id", $id);
		$data = $this->db->get()->result();

		if($data){
			$response['success'] = true;
			$response['data'] = $data[0];
		}

		return $response;
	}
}	