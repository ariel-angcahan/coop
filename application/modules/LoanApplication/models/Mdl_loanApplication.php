<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class mdl_loanApplication extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function get_borrower_list(){
		$response['success'] = false;
		$this->db->select("id, CONCAT(first_name, ' ', middle_name, ' ', last_name) as borrower_name");
		$this->db->from('person_info');
		$result = $this->db->get()->result();

		if(!empty($result)){
			$response['success'] = true;
			$response['data'] = $result;
		}

		return $response;
	}

	function create_loan($form_data){
		$response['success'] = false;
		$this->db->insert("loan_borrower_header", $form_data);
		$rows = $this->db->affected_rows();
		if($rows == 1){
			$response['id'] = $this->db->insert_id();
			$response['success'] = true;
		}

		return $response;
	}

	function loan_frequency_of_payment_list(){
		$response['success'] = false;
		$this->db->select("*");
		$this->db->from("loan_frequency_of_payment_mst");
		$result = $this->db->get()->result();
		if(!empty($result)){
			$response['success'] = true;
			$response['data'] = $result;
		}

		return $response;
	}

	function loan_deduction_list(){
		$response['success'] = false;
		$this->db->select("*");
		$this->db->from("loan_deduction_mst");
		$this->db->where("isActive", 1);
		$result = $this->db->get()->result();
		if(!empty($result)){
			$response['success'] = true;
			$response['data'] = $result;
		}

		return $response;
	}

	function create_deduction($deduction_data){
		$response['success'] = false;
		$this->db->insert("loan_borrower_deductions", $deduction_data);
		$rows = $this->db->affected_rows();
		if($rows == 1){
			$response['id'] = $this->db->insert_id();
			$response['success'] = true;
		}

		return $response;
	}

	function other_loan_status($borrower_id){
		$response['success'] = false;
		$this->db->select("*");
		$this->db->from("loan_borrower_header");
		$this->db->where("person_info_id", $borrower_id);
		$this->db->where("loan_status", 1);
		$result = $this->db->get()->result();
		if(!empty($result)){
			$response['success'] = true;
		}

		return $response;
	}

    function subscription_mst($approved_member_id){
        $this->db->select('id');
        $this->db->from('member_subscription_mst');
        $this->db->where('approved_member_id', $approved_member_id);
        return $this->db->get()->result();
    }

    function member_ledger_header($subscription_id){
        $this->db->select('id');
        $this->db->from('member_ledger_header');
        $this->db->where('subscription_id', $subscription_id);
        return $this->db->get()->result();
    }

    function member_ledger_details($member_ledger_header_id){
        $this->db->select('paid_amount');
        $this->db->from('member_ledger_details');
        $this->db->where('member_ledger_header_id', $member_ledger_header_id);
        return $this->db->get()->result();
    }
}	