 <?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class mdl_loanReleased extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function loan_released_list(){
        $response['success'] = false;
        $this->db->select("CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name) as name, lbh.loan_amount, lbh.loan_date, lbh.maturity_date");
        $this->db->from('loan_borrower_header as lbh');
        $this->db->join('person_info as pi', ' lbh.person_info_id = pi.id');
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }
        return $response;
    }

// start of datatable **********************************************************************************************************
    function loan_borrower_list_query() {
        $this->db->distinct();
        $this->db->select("lbh.person_info_id, CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name) as name, (select SUM(loan_amount) from loan_borrower_header where person_info_id = lbh.person_info_id) as loan_amount, (select count(*) from loan_borrower_header where person_info_id = lbh.person_info_id) as loan_count");
        $this->db->from('loan_borrower_header as lbh');
        $this->db->join('person_info as pi', ' lbh.person_info_id = pi.id');
        $this->db->where('lbh.loan_flag', 2);
        $this->db->order_by('lbh.id', "desc");

        if (isset($_POST["search"]["value"])) {

            $search = $_POST["search"]["value"];
            $this->db->like("CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name)", $search);
        }
    }

    function loan_borrower_list_table() {

        $this->loan_borrower_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }
        return $this->db->get()->result();
    }

    function get_filtered_loan_borrower_data() {

        $this->loan_borrower_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_loan_borrower_data() {
        $this->db->distinct();
        $this->db->select("lbh.person_info_id, CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name) as name, (select SUM(loan_amount) from loan_borrower_header where person_info_id = lbh.person_info_id) as loan_amount, (select count(*) from loan_borrower_header where person_info_id = lbh.person_info_id) as loan_count");
        $this->db->from('loan_borrower_header as lbh');
        $this->db->join('person_info as pi', ' lbh.person_info_id = pi.id');
        $this->db->where('lbh.loan_flag', 2);
        $this->db->order_by('lbh.id', "desc");

        return $this->db->count_all_results();  
    }

//  end of datatable ******************************************************************************************************************************


// start of datatable **********************************************************************************************************
    function borrower_loan_list_query() {

        $this->db->select("lbh.id, CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name) as name, lbh.loan_amount, lbh.loan_date, lbh.maturity_date, lsm.status");
        $this->db->from('loan_borrower_header as lbh');
        $this->db->join('person_info as pi', ' lbh.person_info_id = pi.id');
        $this->db->join('loan_status_mst as lsm', 'lbh.loan_status = lsm.id');
        $this->db->where('lbh.loan_flag', 2);
        $this->db->where('lbh.person_info_id', post("id", true));
        $this->db->order_by('lbh.id', "desc");

        if (isset($_POST["search"]["value"])) {

            $search = $_POST["search"]["value"];
            $this->db->like("CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name)", $search);
        }
    }

    function borrower_loan_list_table() {

        $this->borrower_loan_list_query();

        if ($_POST["length"] != -1) {
            $this->db->limit($this->input->post('length'),$this->input->post('start'));
        }
        return $this->db->get()->result();
    }

    function get_filtered_borrower_loan_data() {

        $this->borrower_loan_list_query();

        return $this->db->get()->num_rows();
    }

    function get_all_borrower_loan_data() {

        $this->db->select("lbh.id, CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name) as name, lbh.loan_amount, lbh.loan_date, lbh.maturity_date, lsm.status");
        $this->db->from('loan_borrower_header as lbh');
        $this->db->join('person_info as pi', ' lbh.person_info_id = pi.id');
        $this->db->join('loan_status_mst as lsm', 'lbh.loan_status = lsm.id');
        $this->db->where('lbh.loan_flag', 2);
        $this->db->where('lbh.person_info_id', post("id", true));
        $this->db->order_by('lbh.id', "desc");

        return $this->db->count_all_results();  
    }

//  end of datatable ******************************************************************************************************************************

    function get_borrower_information($id){
        $response['success'] = false;

        $this->db->select("CONCAT(pi.first_name, ' ', pi.middle_name, ' ', pi.last_name) as name, lbh.*");
        $this->db->from('loan_borrower_header as lbh');
        $this->db->join('person_info as pi', 'lbh.person_info_id = pi.id');
        $this->db->where('lbh.id', $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result[0];
        }
        return $response;
    }

    function get_loan_release_header($id){
        $response['success'] = false;
        $this->db->select("*");
        $this->db->from("loan_borrower_header");
        $this->db->where("id", $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response["data"] = $result[0];
            $response["success"] = true;
        }
        return $response;
    }

    function loan_release_details($id){
        $response['success'] = false;
        $this->db->select("*");
        $this->db->from("loan_borrower_details");
        $this->db->where("loan_borrower_id", $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response["data"] = $result;
            $response["success"] = true;
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

    function get_loan_payment_list($id){
        $response['success'] = false;
        $this->db->select('*, (SELECT SUM(amount) FROM loan_payment_penalty WHERE loan_borrower_details_id = lbd.id) as penalty_amount, (lbd.installment_amount - (SELECT SUM(loan_paid_amount) FROM loan_ledger_details WHERE loan_borrower_details_id = lbd.id)) as balance_amount, IF(lbd.installment_amount - (SELECT SUM(total_paid_amount) FROM loan_ledger_details WHERE loan_borrower_details_id = lbd.id) IS NULL, 1 , 0) AS active_payment');
        $this->db->from('loan_borrower_details as lbd');
        $this->db->where('lbd.loan_borrower_id', $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['data'] = $result;
            $response['success'] = true;
        }
        return $response;
    }

    function generate_penalty(){
        $response['success'] = false;
        $this->db->select('*');
        $this->db->from('loan_borrower_details');
        $this->db->where('due_date < DUEDATE()', null, false);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['data'] = $result;
            $response['success'] = true;
        } 
        return $response;
    }

    function get_penalty_list($id){
        $response['success'] = false;
        $this->db->select('lpp.date_created, lpp.amount');
        $this->db->from('loan_borrower_details AS lbd');
        $this->db->join('loan_payment_penalty AS lpp', 'lbd.id = lpp.loan_borrower_details_id');
        $this->db->where('lbd.id', $id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['data'] = $result;
            $response['success'] = true;
        }
        return $response;
    }

    function insert_payment_info($amount, $loan_borrower_id){
        $this->db->set("payment_amount", $amount)
                ->set('loan_borrower_id', $loan_borrower_id)
                ->insert('loan_ledger_header');
        return $this->db->insert_id();
    }

    function current_payment_details($loan_borrower_id){
        $response['success'] = false;

        $this->db->limit(1);
        $this->db->select("*, IFNULL((installment_amount - (SELECT SUM(loan_paid_amount) FROM loan_ledger_details WHERE loan_borrower_details_id = lbd.id)), lbd.installment_amount) AS due_date_balance, ((SELECT SUM(amount) FROM loan_payment_penalty WHERE loan_borrower_details_id = lbd.id) - IFNULL((SELECT SUM(penalty_paid_amount) FROM loan_ledger_details WHERE loan_borrower_details_id = lbd.id), 0)) AS penalty_balance, lbd.id as loan_borrower_details_id");
        $this->db->from('loan_borrower_details AS lbd');
        $this->db->where("IFNULL((installment_amount - (SELECT SUM(loan_paid_amount) FROM loan_ledger_details WHERE loan_borrower_details_id = lbd.id)), lbd.installment_amount) != 0", null, false);
        $this->db->where('lbd.loan_borrower_id', $loan_borrower_id);
        $result = $this->db->get()->result();

        if(!empty($result)){
            $response['data'] = $result[0];
            $response['success'] = true;
        }
        return $response;
    }

    function insert_loan_ledger_detail($form_data){
        $response['success'] = false;

        $this->db->insert("loan_ledger_details", $form_data);
        $inserted_id = $this->db->insert_id();

        if(!empty($inserted_id)){
            $response['success'] = true;
        }
        return $response;
    }

    function get_payment_transaction_details($id){
        $response['success'] = false;

        $result = $this->db->select('*')
                    ->from('loan_ledger_details')
                    ->where('loan_borrower_details_id', $id)
                    ->get()
                    ->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }
        return $response;
    }

    function get_loan_history_list($id){
        $response['success'] = false;

        $result = $this->db->select('*')
                    ->from('loan_ledger_header')
                    ->where('loan_borrower_id', $id)
                    ->get()
                    ->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }
        return $response;
    }

    function get_loan_history_details($id){
        $response['succes'] = false;

        $result = $this->db->select('*')
                    ->from('loan_ledger_details as lld')
                    ->join('loan_borrower_details as lbd', 'lld.loan_borrower_details_id = lbd.id')
                    ->where('lld.loan_ledger_header_id', $id)
                    ->get()
                    ->result();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }
        return $response;   
    }

    function get_loan_history_detail($id){
        $response['succes'] = false;

        $result = $this->db->select('*')
                    ->from('loan_ledger_details as lld')
                    ->join('loan_borrower_details as lbd', 'lld.loan_borrower_details_id = lbd.id')
                    ->where('lbd.loan_borrower_id', $id)
                    ->get()
                    ->result();
        // echo $this->db->last_query();
        // exit();

        if(!empty($result)){
            $response['success'] = true;
            $response['data'] = $result;
        }
        return $response;   
    }

    function update_approved_member_total_paid_loan_amount($loan_ledger_header_id, $loan_borrower_details_id){
        $response['success'] = false;

        $this->db->select('person_info_id as id');
        $this->db->from('loan_borrower_header');
        $this->db->where('id', $loan_borrower_details_id);
        $v_person_info = $this->db->get()->result()[0];

        $this->db->select('total_paid_loan_amount as amount');
        $this->db->from('approved_member');
        $this->db->where('person_info_id', $v_person_info->id);
        $v_last_total_paid = $this->db->get()->result()[0];

        $this->db->select('SUM(loan_paid_amount) as amount');
        $this->db->from('loan_ledger_details');
        $this->db->where('loan_ledger_header_id', $loan_ledger_header_id);
        $v_total_loan_paid = $this->db->get()->result()[0];

        $this->db->set('total_paid_loan_amount', ($v_last_total_paid->amount + $v_total_loan_paid->amount));
        $this->db->where('person_info_id', $v_person_info->id);
        $this->db->update('approved_member');
        $rows = $this->db->affected_rows();

        if(count($rows)){
            $response['success'] = true;
        }

        return $response;
    }
}   