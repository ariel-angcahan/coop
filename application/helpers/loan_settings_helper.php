<?php if( ! defined('BASEPATH') ) exit('NO direct script access allowed');
class loan_settings{

	public function __construct(){
		$this->ci =& get_instance();
	}

	public function loan_settings(){
		$this->ci->db->select('*');
		$this->ci->db->from('loan_settings');
		$result = $this->ci->db->get()->result();
		return $result[0];
	}
}

function loan_grt_rate(){
	$loan_settings = new loan_settings();
	return $loan_settings->loan_settings()->loan_grt_rate;
}

function loan_interest_rate(){
	$loan_settings = new loan_settings();
	return $loan_settings->loan_settings()->loan_interest_rate;
}

function loanable_rate(){
	$loan_settings = new loan_settings();
	return $loan_settings->loan_settings()->loanable_rate;
}