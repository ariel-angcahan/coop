<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_subscriptionTransactionLogs extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

// start of datatable **********************************************************************************************************
    function subscription_transaction_list_query() {

        $this->db->select("mtls.id, am.tmc_code, CONCAT(pi.last_name, ', ', pi.first_name,' ', pi.middle_name) as member_name, mtls.amount, mtls.date_transact");
        $this->db->from("member_transaction_logs as mtls");
        $this->db->join("member_subscription_mst as sm", "mtls.subscription_id = sm.id");
        $this->db->join("approved_member as am", "sm.approved_member_id = am.id");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");
        if(isset($_POST["from"]) && isset($_POST["to"]) && !empty($_POST["from"]) && !empty($_POST["to"])){
            $from = new DateTime($this->input->post('from'));
            $to = new DateTime($this->input->post('to'));
            $this->db->where("(DATE(mtls.date_transact) BETWEEN '".$from->format('Y-m-d')."' AND '".$to->format('Y-m-d')."')", null, false);
        }

        if (!empty(isset($_POST["search"]["value"]))) {
            $search = $_POST["search"]["value"];
            $this->db->like("(am.tmc_code", $search);
            $this->db->or_like("CONCAT(pi.last_name, ', ', pi.first_name,' ', pi.middle_name))", $search);
        }

        $this->db->order_by('mtls.id', 'desc');
    }

    function subscription_transaction_list_table() {

        $this->subscription_transaction_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_subscription_transaction_data() {

        $this->subscription_transaction_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_subscription_transaction_data() {

        $this->db->select("mtls.id, am.tmc_code, CONCAT(pi.last_name, ', ', pi.first_name,' ', pi.middle_name) as member_name, mtls.amount, mtls.date_transact");
        $this->db->from("member_transaction_logs as mtls");
        $this->db->join("member_subscription_mst as sm", "mtls.subscription_id = sm.id");
        $this->db->join("approved_member as am", "sm.approved_member_id = am.id");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");
        if(isset($_POST["from"]) && isset($_POST["to"]) && !empty($_POST["from"]) && !empty($_POST["to"])){
            $from = new DateTime($this->input->post('from'));
            $to = new DateTime($this->input->post('to'));
            $this->db->where("(DATE(mtls.date_transact) BETWEEN '".$from->format('Y-m-d')."' AND '".$to->format('Y-m-d')."')", null, false);
        }

        $this->db->order_by('mtls.id', 'desc');
        return $this->db->count_all_results();  
    }

    //  end of datatable ******************************************************************************************************************************

    function get_transaction_details($id){
        $response['success'] = false;
        $this->db->select('mlh.due_date, mld.paid_amount, mld.date_paid');
        $this->db->from('member_ledger_details as mld');
        $this->db->join('member_ledger_header as mlh', 'mld.member_ledger_header_id = mlh.id');
        $this->db->where('transaction_log_id', $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    function transaction_logs_report($from, $to){
        $response['success'] = false;
        $this->db->select("mtls.id, am.tmc_code, CONCAT(pi.last_name, ', ', pi.first_name,' ', pi.middle_name) as member_name, mtls.amount, mtls.date_transact");
        $this->db->from("member_transaction_logs as mtls");
        $this->db->join("member_subscription_mst as sm", "mtls.subscription_id = sm.id");
        $this->db->join("approved_member as am", "sm.approved_member_id = am.id");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");
        $this->db->where("(DATE(mtls.date_transact) BETWEEN '".$from->format('Y-m-d')."' AND '".$to->format('Y-m-d')."')", null, false);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }
}	