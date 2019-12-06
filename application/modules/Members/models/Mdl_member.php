<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class mdl_member extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}
// start of datatable **********************************************************************************************************
    function member_list_query() {

        $this->db->select("am.id, am.person_info_id, pi.first_name, pi.middle_name, pi.last_name, am.tmc_code, am.date_approved, pi.date_created");
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

        $this->db->select("am.id, am.person_info_id, pi.first_name, pi.middle_name, pi.last_name, am.tmc_code, am.date_approved, pi.date_created");
        $this->db->from("approved_member as am");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");

        return $this->db->count_all_results();  
    }

    //  end of datatable ******************************************************************************************************************************

// start of datatable **************************************************************************************************
    function subscription_list_query() {

        $this->db->select("sm.id, sm.subscription_amount, sm.date_approved, sm.date_created, pmm.`mode`");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("payment_mode_mst as pmm", "sm.payment_mode_id = pmm.id");
        $this->db->where('sm.approved_flag', 1);
        $this->db->where('sm.approved_member_id', $this->input->post('id'));
    }

    function subscription_list_table() {

        $this->subscription_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_subscription_list_data() {

        $this->subscription_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_subscription_list_data() {

        $this->db->select("sm.id, sm.subscription_amount, sm.date_approved, sm.date_created, pmm.`mode`");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("payment_mode_mst as pmm", "sm.payment_mode_id = pmm.id");
        $this->db->where('sm.approved_flag', 0);
        $this->db->where('sm.approved_member_id', $this->input->post('id'));

        return $this->db->count_all_results();  
    }
// end of datatable *****************************************************************************************************


    // start of datatable **************************************************************************

    function ledger_detail_table($ledger_header_id) {
        $response['success'] = false;
        $this->db->select("mld.paid_amount, mld.date_paid,(select SUM(amount_to_paid) from member_ledger_header where subscription_id = ".$ledger_header_id.") as grand_total, mlh.due_date, mld.transaction_log_id");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join('member_ledger_header as mlh', 'sm.id = mlh.subscription_id');
        $this->db->join('member_ledger_details as mld', 'mlh.id = mld.member_ledger_header_id');
        $this->db->where("sm.id", $ledger_header_id);
        $this->db->order_by('mld.id', 'asc');
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

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

    function get_payment_account_details($tmc_code){
        $response['success'] = false;
        $this->db->select('mlh.id, pi.first_name, pi.middle_name, pi.last_name, mlh.amount_to_paid, mlh.due_date, (select SUM(paid_amount) from member_ledger_details where member_ledger_header_id = mlh.id) as total_paid_amount');
        $this->db->from('approved_member as am');
        $this->db->join('member_subscription_mst as sm', 'am.id = sm.approved_member_id');
        $this->db->join('member_ledger_header as mlh', 'sm.approved_member_id = mlh.subscription_id');
        $this->db->join('person_info as pi', 'am.person_info_id = pi.id');
        $this->db->where('sm.current_subscription', 1);
        $this->db->where('am.tmc_code', $tmc_code);
        $data = $this->db->get()->result();

        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data;
        }

        return $response;
    }

    function payment_proceed($form_data, $tmc_code){
        $response['success'] = false;
        $insert = $this->db->insert('member_ledger_details', $form_data);
        if($insert){
            $balance = currentSubscriptionBalance($tmc_code);
            if($balance == 0){
                $this->db->set('current_subscription', 0);
                $this->db->set('total_paid', getTotalPaidByTmcCode($tmc_code) + $form_data['paid_amount']);
                $this->db->set('date_fully_paid', 'NOW()', false);
                $this->db->where('id', geSubscriptionIdByTmcCode($tmc_code));
                $this->db->update('member_subscription_mst');
            }else{
                $this->db->set('total_paid', getTotalPaidByTmcCode($tmc_code) + $form_data['paid_amount']);
                $this->db->where('id', geSubscriptionIdByTmcCode($tmc_code));
                $this->db->update('member_subscription_mst');
            }

            $response['success'] = true;
        }
        return $response;
    }

    // start of datatable ********************************************************************************************************
    function subscription_request_list_query() {

        $this->db->select("pi.first_name, pi.middle_name, pi.last_name, sm.id, am.tmc_code, sm.date_created, sm.subscription_amount, sm.payment_per_mode, pmm.mode");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("approved_member as am", "sm.approved_member_id = am.id");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");
        $this->db->join("payment_mode_mst as pmm", "sm.payment_mode_id = pmm.id");
        $this->db->where("sm.approved_flag", 0);

        if (isset($_POST["search"]["value"])) {

            $search = $_POST["search"]["value"];
            $this->db->like("am.tmc_code", $search);        
        }
    }

    function subscription_request_table() {

        $this->subscription_request_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_subscription_request_data() {

        $this->subscription_request_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_subscription_request_data() {

        $this->db->select("pi.first_name, pi.middle_name, pi.last_name, sm.id, am.tmc_code, sm.date_created ");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("approved_member as am", "sm.approved_member_id = am.id");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");
        $this->db->where("sm.approved_flag", 0);

        return $this->db->count_all_results();  
    }

    // end of datatable **************************************************************************************************************

    function get_subscription_request_info($id){
        $response['success'] = false;

        $this->db->select("pi.first_name, pi.middle_name, pi.last_name, sm.id, am.tmc_code, sm.date_created, sm.subscription_amount, sm.payment_per_mode, pmm.mode");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("approved_member as am", "sm.approved_member_id = am.id");
        $this->db->join("person_info as pi", "am.person_info_id = pi.id");
        $this->db->join("payment_mode_mst as pmm", "sm.payment_mode_id = pmm.id");
        $this->db->where("sm.approved_flag", 0);
        $this->db->where("sm.id", $id);
        $data = $this->db->get()->result();

        if($data){
            $response['data'] = $data[0];
            $response['success'] = true;
        }

        return $response;
    }

    function approve_subscription_request($id){
        $response['success'] = false;
        $this->db->where('id', $id);
        $update = $this->db->update('member_subscription_mst', array('approved_flag' => 1));
        if($update){
            $response['success'] = true;
        }

        return $response;
    }

    function get_payment_accont_details($tmc_code){
        $response['success'] = false;
        $this->db->select('*, (select sum(paid_amount) from member_ledger_details where member_ledger_header_id = mlh.id) as total_paid_amount');
        $this->db->from('approved_member as am');
        $this->db->join('person_info as pi', 'am.person_info_id = pi.id');
        $this->db->join('member_subscription_mst as sm', 'am.id = sm.approved_member_id');
        $this->db->join('member_ledger_header as mlh', 'sm.id = mlh.subscription_id');
        $this->db->where('sm.current_subscription', 1);
        $this->db->where('am.tmc_code', $tmc_code);
        $result = $this->db->get()->result();
        
        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    function get_subscription_information($subscription_id){

        $response['success'] = false;
        $this->db->select('subscription_amount, payment_mode_id, payment_per_mode');
        $this->db->from('member_subscription_mst');
        $this->db->where('id', $subscription_id);
        $data = $this->db->get()->result();

        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
    }

    function member_ledger_header($insert_ledger){
        $response['success'] = false;
        $insert = $this->db->insert('member_ledger_header', $insert_ledger);
        if($insert){
            $response['success'] = true;
        }
        return $response;
    }

    function update_subscription_approve_flag($subscription_id){
        $this->db->where('id', $subscription_id);
        $this->db->set('current_subscription', 1);
        $this->db->set('approved_flag', 1);
        $this->db->set('date_approved',  'NOW()', false);
        $update = $this->db->update('member_subscription_mst');
        if($update){
            $response['success'] = true;
        }

        return $response;
    }

    function insert_new_request($approved_member_id,$request_subscriptiom_amount,$subscription_payment_mode,$request_payment_per_mode_amount){
        $response['success'] = false;
        $this->db->set('approved_member_id', $approved_member_id);
        $this->db->set('subscription_amount', $request_subscriptiom_amount);
        $this->db->set('payment_mode_id', $subscription_payment_mode);
        $this->db->set('payment_per_mode', $request_payment_per_mode_amount);
        $this->db->insert('member_subscription_mst');
        $insert = $this->db->affected_rows();
        if($insert){
            $response['success'] = true;
        }

        return $response;
    }

    function get_account_holder($tmc_code){
        $response['success'] = false;
        $this->db->select('pi.last_name, pi.first_name, pi.middle_name');
        $this->db->from('person_info as pi');
        $this->db->join('approved_member as am', 'pi.id = am.person_info_id');
        $this->db->where('tmc_code', $tmc_code);
        $data = $this->db->get()->result();
        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
    }

    function print_break_down_table($id){
        $response['success'] = false;
        $this->db->select("mlh.amount_to_paid, mlh.due_date, (select SUM(paid_amount) from member_ledger_details where member_ledger_header_id = mlh.id) as total_paid_amount");
        $this->db->from("member_subscription_mst as sm");
        $this->db->join("member_ledger_header as mlh", "sm.id = mlh.subscription_id");
        $this->db->where("sm.id", $id);
        $result = $this->db->get()->result();
        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }

        return $response;    
    }

    function insert_transaction_logs($amount, $subscription_id){
        $response['success'] = false;
        $this->db->set("amount", $amount);
        $this->db->set("subscription_id", $subscription_id);
        $this->db->insert('member_transaction_logs');
        $insert = $this->db->affected_rows();

        if(!empty($insert)){
            $response['success'] = true;
            $response['id'] = $this->db->insert_id();
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
