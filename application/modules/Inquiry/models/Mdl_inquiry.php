<?php 

class Mdl_inquiry extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	// start of datatable **************************************************************************************************
    function subscription_list_query($approved_member_id) {

        $this->db->select("sm.id, sm.subscription_amount, sm.date_approved, sm.date_created, pmm.`mode`");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("payment_mode_mst as pmm", "sm.payment_mode_id = pmm.id");
        $this->db->where('sm.approved_flag', 1);
        $this->db->where('sm.approved_member_id', $approved_member_id);
    }

    function subscription_list_table($approved_member_id) {

        $this->subscription_list_query($approved_member_id);

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_subscription_list_data($approved_member_id) {

        $this->subscription_list_query($approved_member_id);

        return $this->db->get()->num_rows();
    }

    function get_all_subscription_list_data($approved_member_id) {

        $this->db->select("sm.id, sm.subscription_amount, sm.date_approved, sm.date_created, pmm.`mode`");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("payment_mode_mst as pmm", "sm.payment_mode_id = pmm.id");
        $this->db->where('sm.approved_flag', 0);
        $this->db->where('sm.approved_member_id', $approved_member_id);

        return $this->db->count_all_results();  
    }
// end of datatable *****************************************************************************************************

    function get_ledger_header($id){

        $response['success'] = false;
        $this->db->select('pi.first_name, pi.middle_name, pi.last_name, sm.subscription_amount, am.tmc_code, am.date_approved as start_date,(select due_date from member_ledger_header where subscription_id = sm.id order by due_date desc limit 1) as due_date, (select SUM(paid_amount) from member_ledger_details where member_ledger_header_id IN (select id from member_ledger_header where subscription_id = sm.id)) as total_amount_paid');
        $this->db->from('member_subscription_mst as sm');
        $this->db->join('approved_member as am', 'sm.approved_member_id = am.id');
        $this->db->join('person_info as pi', 'am.person_info_id = pi.id');
        $this->db->where('sm.id', $id);
        $data = $this->db->get()->result();

        if($data){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
    }
    
    function ledger_detail_table($ledger_header_id) {
        $response['success'] = false;
        $this->db->select("mld.paid_amount, mld.date_paid,(select SUM(amount_to_paid) from member_ledger_header where subscription_id = ".$ledger_header_id.") as grand_total, mlh.due_date, mld.transaction_log_id");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join('member_ledger_header as mlh', 'sm.id = mlh.subscription_id');
        $this->db->join('member_ledger_details as mld', 'mlh.id = mld.member_ledger_header_id');
        $this->db->where("sm.id", $ledger_header_id);
        $this->db->order_by('mld.date_paid', 'asc');
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }


    function break_down_table($id) {
        $response['success'] = false;
        $this->db->select("mlh.amount_to_paid, mlh.due_date, (select SUM(paid_amount) from member_ledger_details where member_ledger_header_id = mlh.id) as total_paid_amount");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("member_ledger_header as mlh", "sm.id = mlh.subscription_id");
        $this->db->where("sm.id", $id);
        $this->db->order_by("mlh.id", "asc");
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    function get_subscription_transaction($subscription_id){
        $response['success'] = false;
        $this->db->select('id, amount, date_transact');
        $this->db->from('member_transaction_logs');
        $this->db->where('subscription_id', $subscription_id);
        $result = $this->db->get()->result();
        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

}	