<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_penalty extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	// start of datatable **********************************************************************************************************
    function loan_penalty_list_query() {
        $this->db->select("lbd.id, lbd.loan_borrower_id, lbd.due_date, lbd.installment_amount, (lbd.installment_amount - IFNULL((SELECT SUM(lld.loan_paid_amount) FROM loan_ledger_details AS lld WHERE lld.loan_borrower_details_id = lbd.id), 0)) AS balance")
        		->from('loan_borrower_details AS lbd')
        		->where('lbd.due_date < NOW()', null, false)
        		->where('(lbd.installment_amount - IFNULL((SELECT SUM(lld.loan_paid_amount) FROM loan_ledger_details AS lld WHERE lld.loan_borrower_details_id = lbd.id), 0)) > 0', null, false);

        // if (isset($_POST["search"]["value"])) {

        //     $search = $_POST["search"]["value"];
        //     $this->db->like("CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name)", $search);
        // }
    }

    function loan_penalty_list_table() {

        $this->loan_penalty_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_loan_penalty_data() {

        $this->loan_penalty_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_loan_penalty_data() {
        $this->db->select("lbd.id, lbd.loan_borrower_id, lbd.due_date, lbd.installment_amount, (lbd.installment_amount - IFNULL((SELECT SUM(lld.loan_paid_amount) FROM loan_ledger_details AS lld WHERE lld.loan_borrower_details_id = lbd.id), 0)) AS balance")
        		->from('loan_borrower_details AS lbd')
        		->where('lbd.due_date < NOW()', null, false)
        		->where('(lbd.installment_amount - IFNULL((SELECT SUM(lld.loan_paid_amount) FROM loan_ledger_details AS lld WHERE lld.loan_borrower_details_id = lbd.id), 0)) > 0', null, false);

        return $this->db->count_all_results();  
    }

//  end of datatable ******************************************************************************************************************************
}	