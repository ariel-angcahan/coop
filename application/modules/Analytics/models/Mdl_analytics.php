<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class mdl_analytics extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

// start of datatable **********************************************************************************************************
    function member_list_query() {

        $this->db->select("am.id, am.person_info_id, pi.first_name, pi.middle_name, pi.last_name, am.tmc_code");
        $this->db->from("approved_member as am");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");

        if (isset($_POST["search"]["value"])) {

            $search = $_POST["search"]["value"];
            $this->db->like("am.tmc_code", $search);
        }
    }

    function member_list_table() {

        $this->member_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_member_data() {

        $this->member_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_member_data() {

        $this->db->select("am.id, am.person_info_id, pi.first_name, pi.middle_name, pi.last_name, am.tmc_code");
        $this->db->from("approved_member as am");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");

        return $this->db->count_all_results();  
    }

    //  end of datatable ******************************************************************************************************************************

    function get_subscription_list($approved_member_id){
        $response['success'] = false;
        $this->db->select('*');
        $this->db->from('member_subscription_mst');
        $this->db->where('approved_member_id', $approved_member_id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    function get_subscription_details($id){
        $response['success'] = false;
        $this->db->select('(((SELECT count(*) FROM member_ledger_details as mld WHERE mld.member_ledger_header_id = mlh.id and CONVERT(mld.date_paid, DATE) <= CONVERT(mlh.due_date, DATE)) / (SELECT count(*) FROM member_ledger_details WHERE member_ledger_header_id = mlh.id)) * 100) as payment_on_time');
        $this->db->from('member_ledger_header as mlh');
        $this->db->where('mlh.subscription_id', $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    function get_subscription_detail_list($subscription_id){
        $response['success'] = false;
        $this->db->select('mlh.due_date, (SELECT count(*) FROM member_ledger_details as mld WHERE mld.member_ledger_header_id = mlh.id and CONVERT(mld.date_paid, DATE) < CONVERT(mlh.due_date, DATE)) as on_time_count, (((SELECT count(*) FROM member_ledger_details as mld WHERE mld.member_ledger_header_id = mlh.id and CONVERT(mld.date_paid, DATE) < CONVERT(mlh.due_date, DATE)) / (SELECT count(*) FROM member_ledger_details WHERE member_ledger_header_id = mlh.id)) * 100) as payment_on_time_rating, (SELECT count(*) FROM member_ledger_details as mld WHERE mld.member_ledger_header_id = mlh.id and CONVERT(mld.date_paid, DATE) > CONVERT(mlh.due_date, DATE)) as delay_count, (((SELECT count(*) FROM member_ledger_details as mld WHERE mld.member_ledger_header_id = mlh.id and CONVERT(mld.date_paid, DATE) > CONVERT(mlh.due_date, DATE)) / (SELECT count(*) FROM member_ledger_details WHERE member_ledger_header_id = mlh.id)) * 100) as payment_delay_rating');
        $this->db->from('member_ledger_header AS mlh');
        $this->db->where('mlh.subscription_id', $subscription_id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    function grand_total_amount($approved_member_id){
        $response['success'] = false;
        $this->db->select('SUM(total_paid) as total_paid');
        $this->db->from('member_subscription_mst');
        $this->db->where('approved_member_id', $approved_member_id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            return $result[0]->total_paid;
        }else{
            return 0;
        }

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