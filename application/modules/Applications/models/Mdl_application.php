<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class mdl_application extends CI_Model{
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}


    function registered_list_query() {

    	$this->db->select("id, first_name, middle_name, last_name, date_created");
    	$this->db->from("person_info_tmp");
        $this->db->where("approved_flag", 0);

        if (isset($_POST["search"]["value"])) {

            $search = $_POST["search"]["value"];
            $search = explode("-", $search);
            if(!empty($search[1])){
           		$this->db->like("id", $search[1]);
            }
        }
    }

    function registered_list_table() {

        $this->registered_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }

        return $this->db->get()->result();
    }

    function get_filtered_registered_data() {

        $this->registered_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_registered_data() {

    	$this->db->select("id, first_name, middle_name, first_name, date_created");
    	$this->db->from("person_info_tmp");
        $this->db->where("approved_flag", 0);

        return $this->db->count_all_results();  
    }

    function get_application_information($id){
        $response['success'] = false;
        $this->db->select('*, mam.address as market_address, mm.type as membership_type, pi.id as tmp_id, pmm.mode as payment_mode');
        $this->db->from('person_info_tmp as pi');
        $this->db->join('market_addr_mst as mam', 'pi.market_addr_id = mam.id');
        $this->db->join('membership_mst as mm', 'pi.membership_type_id = mm.id');
        $this->db->join('payment_mode_mst as pmm', 'pi.payment_mode_id = pmm.id');
        $this->db->where('pi.id', $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result[0];
        }

        return $response;
    }

    function get_subscription_information($subscription_id){

        $response['success'] = false;
        $this->db->select('subscription_amount, payment_mode_id, payment_per_mode');
        $this->db->from('member_subscription_mst');
        $this->db->where('id', $subscription_id);
        $this->db->where('approved_flag', 1);
        $this->db->where('date_approved IS NOT NULL', null, false);
        $data = $this->db->get()->result();

        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
    }

    function insert_update($applicant_id, $image_name){
        $response['success'] = false;
        $this->db->where('id', $applicant_id);
        $update = $this->db->update('person_info_tmp', array('image_name' => $image_name));
        if($update){
            $response['success'] = true;
        }
        return $response;
    }

    function check_image($application_id){
        $response['success'] = false;
        $this->db->select('*');
        $this->db->from('person_info_tmp as sm');
        $this->db->where('id', $application_id);
        $this->db->where("(image_name IS NOT NULL OR image_name != '')", null, false);
        $data = $this->db->get()->result();
        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
    }

    function approve_application($subscription_id){
        $response['success'] = false;
        $this->db->select('*');
        $this->db->from('member_subscription_mst');
        $this->db->where('approved_flag', 1);
        $this->db->where('date_approved IS NOT NULL', null, false);
        $data = $this->db->get()->result();
        if($data){
            $response['success'] = true;
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
        $response['success'] = false;
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

    function insert_person_info($form_data, $id){
        $response['success'] = false;
        $this->db->insert('person_info', $form_data);
        $insert = $this->db->affected_rows();
        if($insert){
            $response['inserted_id'] = $this->db->insert_id();
            $this->db->set('approved_flag', 1);
            $this->db->where('id', $id);
            $this->db->update('person_info_tmp');
            $update = $this->db->affected_rows();
            if(!empty($update)){
                $response['success'] = true;
            }
        }

        return $response;
    }

    function insert_approved_member($id, $tmc){
        $response['success'] = false;
        $this->db->set('person_info_id', $id);
        $this->db->set('tmc_code', $tmc);
        $this->db->insert('approved_member');
        $insert = $this->db->affected_rows();
        if($insert){
            $response['success'] = true;
            $response['inserted_id'] = $this->db->insert_id();
        }

        return $response;
    }

    function insert_subscription_mst($approved_member_id, $subscription_amount, $payment_mode_id, $payment_per_mode){
        $response['success'] = false;
        $this->db->set('approved_member_id', $approved_member_id);
        $this->db->set('subscription_amount', $subscription_amount);
        $this->db->set('payment_mode_id', $payment_mode_id);
        $this->db->set('payment_per_mode', $payment_per_mode);
        $this->db->set('current_subscription', 1);
        $this->db->set('approved_flag', 1);
        $this->db->set('date_approved',  'NOW()', false);
        $this->db->insert('member_subscription_mst');
        $insert = $this->db->affected_rows();
        if($insert){
            $response['success'] = true;
            $response['inserted_id'] = $this->db->insert_id();
        }

        return $response;
    }
}	