<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class mdl_loanReleasing extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function get_borrower_list(){
		$response['success'] = false;
		$this->db->select("lbm.id, CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name) as borrower_name, lbm.loan_amount");
		$this->db->from('loan_borrower_header as lbm');
		$this->db->join('person_info as pi', 'lbm.person_info_id = pi.id');
		$this->db->where('lbm.loan_flag', 1);
		$this->db->order_by('lbm.id', 'desc');
		$result = $this->db->get()->result();

		if(!empty($result)){
			$response['success'] = true;
			$response['data'] = $result;
		}

		return $response;
	}

	function get_borrower_info($lbid){
		$response['success'] = false;
		$this->db->select('*');
		$this->db->from('loan_borrower_header');
		$this->db->where('id', $lbid);
		$result = $this->db->get()->result();

		if(!empty($result)){
			$response['success'] = true;
			$response['data'] = $result[0];
		}

		return $response;
	}

	function get_borrower_deductions($lbid){
		$response['success'] = false;
		$this->db->select('*');
		$this->db->from('loan_borrower_deductions');
		$this->db->where('lbid', $lbid);
		$result = $this->db->get()->result();
		if(!empty($result)){
			$response['data'] = $result;
			$response['success'] = true;
		}
		
		return $response;
	}

	function get_borrower_deductions_not_deducted_to_net_proceed($lbid){
		$response['success'] = false;
		$this->db->select('rate, amount');
		$this->db->from('loan_borrower_deductions');
		$this->db->where('lbid', $lbid);
		$this->db->where('deduct_net_proceed_flag', 0);
		$this->db->where('amortized_flag', 1);
		$result = $this->db->get()->result();
		if(!empty($result)){
			$response['data'] = $result;
			$response['success'] = true;
		}
		
		return $response;
	}

	function release($lbid){
		$response['success'] = false;
		$this->db->set('loan_flag', 2);
		$this->db->where('loan_flag', 1);
		$this->db->where('id', $lbid);
		$this->db->update('loan_borrower_header');
		$rows = $this->db->affected_rows();

		if(!empty($rows)){
			$response['success'] = true;
		}

		return $response;
	}

	function insert_amortization($form_data){
		$response['success'] = false;
		$this->db->insert("loan_borrower_details", $form_data);
		$rows = $this->db->affected_rows();

		if(!empty($rows)){
			$response['success'] = true;
		}

		return $response;
	}
}	