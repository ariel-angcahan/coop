<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends MX_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_member');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
    }

    public function main() {
        
        $data['generated_token'] = $this->security->get_csrf_hash();

        $this->load->view('standard_layout.php', $data);
    }

    public function member_list() {

        $datas = $this->Mdl_member->member_list_table();

        $data = array();
        foreach ($datas as $index => $row) {
            $date_created = new DateTime($row->date_created);
            $date_approved = new DateTime($row->date_approved);

            $sub_array = array();
            $sub_array[] = ++$index;
            $sub_array[] = ucfirst($row->first_name) . " " . ucfirst($row->middle_name) . " " . ucfirst($row->last_name);
            $sub_array[] = '<label class="copy">'.strtoupper($row->tmc_code).'</label>';
            // $sub_array[] = $date_created->format('Y-m-d h:i:s a');
            $sub_array[] = $date_created->format('D')." | ".$date_created->format('M d, Y');
            $sub_array[] = $date_approved->format('D')." | ".$date_approved->format('M d, Y');
            $sub_array[] = '<div class="btn-group">
                                <button type="button" class="btn dropdown-toggle bg-purple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ACTIONS <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" class="btn-preview-info" data-id="'.$row->id.'" preview-id="'.$row->person_info_id.'">Member\'s Info</a></li>
                                    <li><a href="javascript:void(0);" class="btn-preview-subscription" data-id="'.$row->id.'">Subscription</a></li>
                                </ul>
                            </div>';
            $data[] = $sub_array;
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_member->get_all_member_data(),  
            "recordsFiltered"   => $this->Mdl_member->get_filtered_member_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
    }

    public function subscription_list() {

        $datas = $this->Mdl_member->subscription_list_table();

        $data = array();
        foreach ($datas as $index => $row) {

            $date_created = new DateTime($row->date_created);
            $date_approved = new DateTime($row->date_approved);
            $balance = getBalanceBySubscriptionId($row->id);
            $status;
            if($balance == 0 && $balance != null){
                $status = '<label class="col-red">FULLY PAID</label>';
            }else{
                $status = '<label class="col-green">ACTIVE</label>';
            }
            $sub_array = array();
            $sub_array[] = ++$index;
            $sub_array[] = number_format($row->subscription_amount, 2);
            $sub_array[] = strtoupper($row->mode);
            $sub_array[] = $date_created->format('D')." | ".$date_created->format('M d, Y');
            $sub_array[] = $date_approved->format('D')." | ".$date_approved->format('M d, Y');
            $sub_array[] = $status;
            $sub_array[] = '<div class="btn-group">
                                <button type="button" class="btn dropdown-toggle bg-purple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ACTIONS <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" class="btn-preview-break-down" data-id="'.$row->id.'">Schedule & Balance</a></li>
                                    <li><a href="javascript:void(0);" class="btn-preview-ledger" data-id="'.$row->id.'">Ledger</a></li>
                                    <li><a href="javascript:void(0);" class="btn-preview-transaction" data-id="'.$row->id.'">Transaction History</a></li>
                                </ul>
                            </div>';
            $data[] = $sub_array;
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_member->get_all_subscription_list_data(),
            "recordsFiltered"   => $this->Mdl_member->get_filtered_subscription_list_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
    }

    public function get_ledger_details(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = $this->input->post('id');
        $datas = $this->Mdl_member->get_ledger_header($id);

        if($datas['success']){
            $start_date = new DateTime($datas['data']->start_date);
            $due_date = new DateTime($datas['data']->due_date);

            $response['name'] = ucfirst($datas['data']->first_name) . " " . ucfirst($datas['data']->middle_name) . " " . ucfirst($datas['data']->last_name);
            $response['subscription_amount'] = number_format($datas['data']->subscription_amount, 2);
            $response['tmc_code'] = strtoupper($datas['data']->tmc_code);
            $response['total_amount_paid'] = number_format($datas['data']->total_amount_paid, 2);
            $response['start_date'] = $start_date->format('M d, Y');
            $response['due_date'] = $due_date->format('M d, Y');
            $response['balance'] = number_format(($datas['data']->subscription_amount - $datas['data']->total_amount_paid), 2);
            $response['ledger'] = $this->ledger_detail($id);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = 'Error while retreiving data!';
        }

        echo json_encode($response);
    }

    public function ledger_detail($ledger_header_id) {

        $datas = $this->Mdl_member->ledger_detail_table($ledger_header_id);
        if($datas['success']){
            $grand_total = $datas['data'][0]->grand_total;
            $arrData = array();
            $paid_amount = 0;
            foreach ($datas['data'] as $index => $row) {
                $paid_amount += $row->paid_amount;
                $sub_array = array();
                $date_paid = new DateTime($row->date_paid);
                $due_date = new DateTime($row->due_date);
                $sub_array['transaction_log_id'] = $row->transaction_log_id;
                $sub_array['paid_amount'] = number_format($row->paid_amount, 2);
                $sub_array['balance'] = number_format(($grand_total - $paid_amount), 2);
                $sub_array['due_date'] = $due_date->format('D')." | ".$due_date->format('M d, Y');
                $sub_array['date_paid'] = $date_paid->format('D')." | ".$date_paid->format('M d, Y h:i:s a');
                array_push($arrData, $sub_array);
            }

            $response['success'] = true;
            $response['ledger_data'] = $arrData;
        }else{
            $response['success'] = false;
        }

        return $response;
    }

    public function get_break_down_details(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = $this->input->post('id');
        $datas = $this->Mdl_member->get_ledger_header($id);

        if($datas['success']){
            $start_date = new DateTime($datas['data']->start_date);
            $due_date = new DateTime($datas['data']->due_date);

            $response['name'] = ucfirst($datas['data']->first_name) . " " . ucfirst($datas['data']->middle_name) . " " . ucfirst($datas['data']->last_name);
            $response['subscription_amount'] = number_format($datas['data']->subscription_amount, 2);
            $response['tmc_code'] = strtoupper($datas['data']->tmc_code);
            $response['total_amount_paid'] = number_format($datas['data']->total_amount_paid, 2);
            $response['start_date'] = $start_date->format('M d, Y');
            $response['due_date'] = $due_date->format('M d, Y');
            $response['balance'] = number_format(($datas['data']->subscription_amount - $datas['data']->total_amount_paid), 2);
            $response['break_down'] = $this->break_down($id);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = 'Error while retreiving data!';
        }

        echo json_encode($response);
    }

    public function break_down($id){

        $datas = $this->Mdl_member->break_down_table($id);
        if($datas['success']){
            $arrData = array();
            foreach ($datas['data'] as $index => $row) {
                $date = new DateTime($row->due_date);
                $sub_array['Lno'] = (++$index);
                $sub_array['due_date'] = $date->format('D')." | ".$date->format('M d, Y');
                $sub_array['amount'] = number_format($row->amount_to_paid, 2);
                $sub_array['balance'] = number_format(($row->amount_to_paid - $row->total_paid_amount), 2);
                array_push($arrData, $sub_array);
            }

            $response['break_down_data'] = $arrData;
            $response['success'] = true;
        }else{
            $response['success'] = false;
        }

        return $response;
    }

    public function get_payment_account_details(){
        $response["generated_token"] = $this->security->get_csrf_hash();
        $tmc_code = trim($this->input->post('tmc_code'));

        $datas = $this->Mdl_member->get_payment_accont_details($tmc_code);
        if($datas['success']){
            $array = array();
            foreach ($datas['data'] as $data) {
                $sub_response = array();
                if(($data->amount_to_paid - $data->total_paid_amount) > 0){
                    $sub_response['id'] = $data->id;
                    $sub_response['cut_of_date'] = $data->due_date . " | " . number_format(($data->amount_to_paid - $data->total_paid_amount), 2);
                    $array[] = $sub_response;
                }
            }
            $response['name'] = ucfirst($datas['data'][0]->first_name) . " " . ucfirst($datas['data'][0]->middle_name) . " " . ucfirst($datas['data'][0]->last_name);
            $response['data'] = $array;
            $response['success'] = true;
        }else{
            $total_subscription_balance = 0;
            foreach (getSubscriptionMasterIdByTmbCode($tmc_code) as $key => $value) {
                $total_subscription_balance += getBalanceBySubscriptionId($sm->id);
            }

            $response['msg'] = $total_subscription_balance . 'Please Contact Adminstrator!';

            if($total_subscription_balance == 0){

                $response['msg'] = 'This Member is already fullypaid!';
            }

            $response['success'] = false;
        }
        echo json_encode($response);
    }

    public function payment_proceed(){
        $response["generated_token"] = $this->security->get_csrf_hash();
        $tmc_code = $this->input->post('tmc_code');
        $amount = str_replace(",", null, $this->input->post('amount'));

        if(empty($tmc_code)){
            $response['success'] = false;
            $response['msg'] = 'Please enter TMC!';
            echo json_encode($response); exit();
        }
        if ($amount <= 0) {
            $response['success'] = false;
            $response['msg'] = 'Amount must be greater than 0.!';
            echo json_encode($response); exit();
        }
        if(empty(intval($amount))){
            $response['success'] = false;
            $response['msg'] = 'Please enter amount!';
            echo json_encode($response); exit();
        }

        if($amount > currentSubscriptionBalance($tmc_code)){
            $response['success'] = false;
            $response['msg'] = 'Amount to pay is higher than subscription balance!';
            echo json_encode($response); exit();
        }
        $subscription_id = geSubscriptionIdByTmcCode($tmc_code);
        if(empty($subscription_id)){
            $response['success'] = false;
            $response['msg'] = 'Cannot find member data on this TMC!';
            echo json_encode($response); exit();
        }
        $result = getCurrentLedgerHeaderToPaid($tmc_code)['data'];
        $balance = ($amount - $result['balance']);
        $this->db->trans_start();
        $log_id = $this->Mdl_member->insert_transaction_logs($amount, $subscription_id);
        if($log_id['success']){
            $transaction_id = $log_id['id'];
        }else{
            $response['success'] = false;
            $response['msg'] = 'Error while inserting transactiom logs!';
            echo json_encode($response); exit();
        }
        if($balance > 0){
            $form_data['member_ledger_header_id'] = $result['ledger_header_id'];
            $form_data['paid_amount'] = $result['balance'];
            $form_data['transaction_log_id'] = $transaction_id;
            $datas = $this->Mdl_member->payment_proceed($form_data, $tmc_code);
            if($datas['success']){
                $this->payment_recursive1($tmc_code, $balance, $transaction_id);
            }
        }else{
            $form_data['member_ledger_header_id'] = $result['ledger_header_id'];
            $form_data['paid_amount'] = $amount;
            $form_data['transaction_log_id'] = $transaction_id;
            $datas = $this->Mdl_member->payment_proceed($form_data, $tmc_code);
            if($datas['success']){
                $this->db->trans_complete();
                $response['success'] = true;
                $response['msg'] = "Payment transacted successfully!";
            }else{
                $response['success'] = false;
                $response['msg'] = 'Error while transacting data!';
            }
            echo json_encode($response);
        }
    }

    public function payment_recursive1($tmc_code, $balance, $transaction_id){
        $response["generated_token"] = $this->security->get_csrf_hash();
        $result = getCurrentLedgerHeaderToPaid($tmc_code)['data'];
        $balance2 = ($balance - $result['balance']);
        if($balance2 > 0){
            $form_data['member_ledger_header_id'] = $result['ledger_header_id'];
            $form_data['paid_amount'] = $result['balance'];
            $form_data['transaction_log_id'] = $transaction_id;
            $datas = $this->Mdl_member->payment_proceed($form_data, $tmc_code);
            if($datas['success']){
                $this->payment_recursive2($tmc_code, $balance2, $transaction_id);
            }
        }else{
            $form_data['member_ledger_header_id'] = $result['ledger_header_id'];
            $form_data['paid_amount'] = $balance;
            $form_data['transaction_log_id'] = $transaction_id;
            $datas = $this->Mdl_member->payment_proceed($form_data, $tmc_code);
            if($datas['success']){
                $this->db->trans_complete();
                $response['success'] = true;
                $response['msg'] = "Payment transacted successfully!";
            }else{
                $response['success'] = false;
                $response['msg'] = 'Error while transacting data!';
            }
            echo json_encode($response);
        }
    }

    public function payment_recursive2($tmc_code, $balance, $transaction_id){
        $response["generated_token"] = $this->security->get_csrf_hash();
        $result = getCurrentLedgerHeaderToPaid($tmc_code)['data'];
        $balance2 = ($balance - $result['balance']);
        if($balance2 > 0){
            $form_data['member_ledger_header_id'] = $result['ledger_header_id'];
            $form_data['paid_amount'] = $result['balance'];
            $form_data['transaction_log_id'] = $transaction_id;
            $datas = $this->Mdl_member->payment_proceed($form_data, $tmc_code);
            if($datas['success']){
                $this->payment_recursive1($tmc_code, $balance2, $transaction_id);
            }
        }else{
            $form_data['member_ledger_header_id'] = $result['ledger_header_id'];
            $form_data['paid_amount'] = $balance;
            $form_data['transaction_log_id'] = $transaction_id;
            $datas = $this->Mdl_member->payment_proceed($form_data, $tmc_code);
            if($datas['success']){
                $this->db->trans_complete();
                $response['success'] = true;
                $response['msg'] = "Payment transacted successfully!";
            }else{
                $response['success'] = false;
                $response['msg'] = 'Error while transacting data!';
            }
            echo json_encode($response);
        }
        
    }

    public function request(){
        
        $data['generated_token'] = $this->security->get_csrf_hash();

        $this->load->view('standard_layout_new_request', $data);
    }

    public function subscription_request(){

        $datas = $this->Mdl_member->subscription_request_table();

        $data = array();
        foreach ($datas as $row) {
            $sub_array = array();
            $date = new DateTime($row->date_created);
            $sub_array[] = ucfirst($row->first_name) . " " . ucfirst($row->middle_name) . " " . ucfirst($row->last_name);
            $sub_array[] = strtoupper($row->tmc_code);
            // $sub_array[] = strtoupper($row->mode);
            $sub_array[] = number_format($row->subscription_amount, 2);
            // $sub_array[] = strtoupper($row->payment_per_mode);
            $sub_array[] = $date->format('M d, Y');
            $sub_array[] = '<a class="btn btn-preview-subscription-request-info bg-blue btn-md" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="More info">
                                <i class="material-icons">info_outline</i>
                            </a>
                            <a class="btn btn-preview-subscription-approved bg-blue btn-md" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Approved?">
                                <i class="material-icons">thumb_up</i>
                            </a>';
            $data[] = $sub_array;
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_member->get_all_subscription_request_data(),  
            "recordsFiltered"   => $this->Mdl_member->get_filtered_subscription_request_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
    }

    public function get_subscription_request_info(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = $this->input->post('id');

        $datas = $this->Mdl_member->get_subscription_request_info($id);
        if($datas['success']){
            $date = new DateTime($datas['data']->date_created);
            $response['date_requested'] = $date->format('M d, Y');
            $response['name'] = ucfirst($datas['data']->first_name) . " " . ucfirst($datas['data']->middle_name) . " " . ucfirst($datas['data']->last_name);
            $response['tmc_code'] = $datas['data']->tmc_code;
            $response['subscription_amount'] = number_format($datas['data']->subscription_amount, 2);
            $response['mode'] = $datas['data']->mode;
            $response['payment_per_mode'] = number_format($datas['data']->payment_per_mode, 2);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = "Error while fetching data!";
        }

        echo json_encode($response);
    }

    public function approve_subscription_request(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = $this->input->post('id');

        $datas = $this->Mdl_member->approve_subscription_request($id);
        if($datas['success']){
            $response['msg'] = "Approved!";
            $response['success'] = true;
        }else{
            $response['msg'] = "Error while approving data!";
            $response['success'] = false;
        }

        echo json_encode($response);
    }

    public function approve_application(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $subscription_id = $this->input->post('subscription-id');
        $balance = currentSubscriptionBalance(getTmcCodeBySubscriotnId($subscription_id));
        if($balance != 0){
            $response['success'] = false;
            $response['msg'] = "Please make sure to Zero the balance of last subscription of this account!";
            echo json_encode($response); exit();
        }

        $subscription_info = $this->Mdl_member->get_subscription_information($subscription_id);
        if($subscription_info['success']){
            $sub_amount = $subscription_info['data']->subscription_amount;
            $payment_per_mode = $subscription_info['data']->payment_per_mode;
            $total_terms =  round($sub_amount / $payment_per_mode);
            if($total_terms < 1){
                $response['success'] = false;
                $response['msg'] = "Subscription amount is less than payment per mode and it is invalid!";
                echo json_encode($response); exit();
            }
            $payment_mode = $subscription_info['data']->payment_mode_id;

            $date_array = array();
            $date = new DateTime();
            for($i=1; $i <= $total_terms; $i++) { 
                if($i == 1){
                    switch($payment_mode){
                        case '1':
                            $date->modify('next sunday');
                            $date->modify('next sunday');
                            // $date = strtotime('next sunday', time());
                        break;
                        case '2':
                            if($date->format('d') < 16){
                                $date->modify('first day of next month');
                                $date->modify('+14 day');
                            }else{
                                $date->modify('last day of next month');
                            }
                        break;
                        case '3':
                            $date->modify('last day of next month');
                            // $date = strtotime('last day of this month', time());
                        break;
                    }
                }else{
                    switch($payment_mode){
                        case '1':
                            // Modify the date it contains
                            $date->modify('next sunday');
                            // $date = strtotime('next sunday', time());
                        break;
                        case '2':
                            if($date->format('d') == 15){
                                $date->modify('last day of this month');
                            }else{
                                $date->modify('first day of next month');
                                $date->modify('+14 day');
                            }
                        break;
                        case '3':
                            // Modify the date it contains
                            $date->modify('last day of next month');
                            // $date = strtotime('last day of next month', time());
                        break;
                    }

                }
                $date_array[] = $date->format('Y-m-d');
                // $display .=  $date->format('Y-m-d')." | ".number_format($sub_amount / $total_terms, 2)."<br>";
            }

            $index = 1;
            foreach ($date_array as $key => $due_date) {
                $insert_ledger['subscription_id'] = $subscription_id;// subscription_id id
                $insert_ledger['row_num'] = $index++;
                $insert_ledger['due_date'] = $due_date;
                $insert_ledger['amount_to_paid'] = $payment_per_mode;
                $ledgers = $this->Mdl_member->member_ledger_header($insert_ledger);
                if(++$key === count($date_array)){
                    $update_flag = $this->Mdl_member->update_subscription_approve_flag($subscription_id);
                    if($update_flag['success']){
                        $response['success'] = true;
                        $response['msg'] = "Member approved!";
                    }else{
                        $response['success'] = false;
                        $response['msg'] = "Error while updating subscription flag!";
                    }
                }
            }

        }else{
            $response['success'] = false;
            $response['msg'] = "No subscription found!";
        }

        echo json_encode($response);
    }

    public function request_new_subscription(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $request_tmc_code = $this->input->post('request-tmc-code');
        $request_subscriptiom_amount = str_replace(",", null, $this->input->post('request-subscriptiom-amount'));
        $subscription_payment_mode = $this->input->post('subscription-payment-mode');
        $request_payment_per_mode_amount = str_replace(",", null, $this->input->post('request-payment-per-mode-amount'));

        $errCount = 0;
        $errArr = array();

        if(empty($request_tmc_code)){
            $errCount++;
            array_push($errArr, 'TMC CODE');
        }

        if(empty($request_subscriptiom_amount)){
            $errCount++;
            array_push($errArr, 'Subscription amount');
        }

        if(empty($subscription_payment_mode)){
            $errCount++;
            array_push($errArr, 'Payment mode');
        }

        if(empty($request_payment_per_mode_amount)){
            $errCount++;
            array_push($errArr, 'Payment per mode');
        }

        if($errCount != 0){
            $response['success'] = false;
            $response['msg'] = implode(", ", $errArr)." is Empty!";
            echo json_encode($response);
            exit();
        }

        $approved_member_id = geApprovedMemberIdByTmcCode($request_tmc_code);
        if(empty($approved_member_id)){
            $response['success'] = false;
            $response['msg'] = "TMC not found!";
            echo json_encode($response);
            exit();
        }

        $result = $this->Mdl_member->insert_new_request($approved_member_id,$request_subscriptiom_amount,$subscription_payment_mode,$request_payment_per_mode_amount);

        if($result['success']){
            $response['success'] = true;
            $response['msg'] = "New subscsription request successfully sent!";
        }else{
            $response['success'] = false;
            $response['msg'] = "No subscription found!";
        }

        echo json_encode($response);
    }

    public function get_account_holder(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $tmc_code = $this->input->post('request-tmc-code');
        $result = $this->Mdl_member->get_account_holder($tmc_code);
        if($result['success']){
            $response['name'] = ucfirst($result['data']->first_name) . " " . ucfirst($result['data']->middle_name) . " " . ucfirst($result['data']->last_name);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = "Cannot find Member!";
        }

        echo json_encode($response);
    }

    public function get_subscription_transaction(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $subscription_id = $this->input->post('id');
        $result = $this->Mdl_member->get_subscription_transaction($subscription_id);
        if($result['success']){
            $arrData = array();
            foreach ($result['data'] as $key => $value) {
                array_push($arrData, $value);
                if(count($result['data']) === ++$key){
                    $response['success'] = true;
                    $response['data'] = $arrData;
                }
            }
        }else{
            $response['msg'] = "No transaction logs found!";
            $response['success'] = false;
        }

        echo json_encode($response);
    }













































// printing area #############################################################################################################################

    public function break_down_print_layout(){
        
        $id = $this->input->get('id');
        $body_html = '';

        $body_header = $this->Mdl_member->get_ledger_header($id);
        if($body_header['success']){
            $start_date = new DateTime($body_header['data']->start_date);
            $due_date = new DateTime($body_header['data']->due_date);

        $body_html .= '<table class="table" width="100%;">
                            <tr>
                                <td>Name: '.ucfirst($body_header['data']->first_name) . " " . ucfirst($body_header['data']->middle_name) . " " . ucfirst($body_header['data']->last_name).'</td>
                                <td>Code: '.strtoupper($body_header['data']->tmc_code).'</td>
                            </tr>
                            <tr>
                                <td>Approved Date: '.$start_date->format('M d, Y').'</td>
                                <td>Balance: '.number_format(($body_header['data']->subscription_amount - $body_header['data']->total_amount_paid), 2).'</td>
                            </tr>
                            <tr>
                                <td>End Date: '.$due_date->format('M d, Y').'</td>
                                <td>Total Paid Amount: '.number_format($body_header['data']->total_amount_paid, 2).'</td>
                            </tr>
                            <tr>
                                <td>Subsciption Amount: '.number_format($body_header['data']->subscription_amount, 2).'</td>
                                <td></td>
                            </tr>
                        </table>';
        }

        $body = $this->Mdl_member->print_break_down_table($id);
        if($body['success']){
            $body_html .= '<hr/>
                            <table width="100%;">
                                <thead>
                                    <tr>
                                        <td style="font-weight: bold;">SEQ.</td>
                                        <td style="font-weight: bold;">CUT OFF DATE</td>
                                        <td style="font-weight: bold;">AMOUNT</td>
                                        <td style="font-weight: bold;">BALANCE</td>
                                    </tr>
                                </thead>
                                <tbody>';
            $total_amount = 0;
            $total_balance = 0;
            foreach ($body['data'] as $index => $row) {
                $date = new DateTime($row->due_date);
                $body_html .=       '<tr>';
                $body_html .=           '<td>'.(++$index).'</td>';
                $body_html .=            '<td>'.$date->format('D')." | ".$date->format('M d, Y').'</td>';
                $body_html .=            '<td>'.number_format($row->amount_to_paid, 2).'</td>';
                $body_html .=         '<td>'.number_format(($row->amount_to_paid - $row->total_paid_amount), 2).'</td>';
                $body_html .=       '</tr>';
                $total_amount += $row->amount_to_paid;
                $total_balance += $row->amount_to_paid - $row->total_paid_amount;
                if(count($body['data']) === $index){
                    $body_html .=       '<tr>';
                    $body_html .=            '<td style="text-align: right; font-weight: bold;">TOTAL:</td>';
                    $body_html .=           '<td></td>';
                    $body_html .=            '<td style="font-weight: bold;">'.number_format($total_amount, 2).'</td>';
                    $body_html .=         '<td style="font-weight: bold;">'.number_format($total_balance, 2).'</td>';
                    $body_html .=       '</tr>';
                }
            }

            $body_html .= '     </tbody>';
            $body_html .= ' </table>';
        }

        $mpdf = new mPDF('utf-8','letter'); 
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($body_html);
        $mpdf->Output();
    }

    public function ledger_print_layout(){
        $id = $this->input->get('id');
        $body_html = '';

        $body_header = $this->Mdl_member->get_ledger_header($id);
        if($body_header['success']){
            $start_date = new DateTime($body_header['data']->start_date);
            $due_date = new DateTime($body_header['data']->due_date);

            $body_html .= '<table class="table" width="100%;">
                                <tr>
                                    <td>Name: '.ucfirst($body_header['data']->first_name) . " " . ucfirst($body_header['data']->middle_name) . " " . ucfirst($body_header['data']->last_name).'</td>
                                    <td>Code: '.strtoupper($body_header['data']->tmc_code).'</td>
                                </tr>
                                <tr>
                                    <td>Approved Date: '.$start_date->format('M d, Y').'</td>
                                    <td>Balance: '.number_format(($body_header['data']->subscription_amount - $body_header['data']->total_amount_paid), 2).'</td>
                                </tr>
                                <tr>
                                    <td>End Date: '.$due_date->format('M d, Y').'</td>
                                    <td>Total Paid Amount: '.number_format($body_header['data']->total_amount_paid, 2).'</td>
                                </tr>
                                <tr>
                                    <td>Subsciption Amount: '.number_format($body_header['data']->subscription_amount, 2).'</td>
                                    <td></td>
                                </tr>
                            </table>';
        }

        $body = $this->Mdl_member->ledger_detail_table($id);

        if($body['success']){
            $body_html .= '<hr/>
                            <table width="100%;">
                                <thead>
                                    <tr>
                                        <td style="font-weight: bold;">SEQ.</td>
                                        <td style="font-weight: bold;">TRANS #</td>
                                        <td style="font-weight: bold;">PAID AMOUNT</td>
                                        <td style="font-weight: bold;">BALANCE</td>
                                        <td style="font-weight: bold;">DUE DATE</td>
                                        <td style="font-weight: bold;">TRANS DATE</td>
                                    </tr>
                                </thead>
                                <tbody>';
            $total_amount = 0;
            $total_balance = 0;
            $Lno = 1;
            foreach ($body['data'] as $index => $row) {
                $paid_amount += $row->paid_amount;
                $date = new DateTime($row->due_date);
                $date_paid = new DateTime($row->date_paid);
                $body_html .=       '<tr>';
                $body_html .=           '<td>'.($Lno).'</td>';
                $body_html .=           '<td>'.$row->transaction_log_id.'</td>';
                $body_html .=           '<td>'.number_format($row->paid_amount, 2).'</td>';
                $body_html .=           '<td>'.number_format(($row->grand_total - $paid_amount), 2).'</td>';
                $body_html .=           '<td>'.$date->format('D')." | ".$date->format('M d, Y').'</td>';
                $body_html .=           '<td>'.$date_paid->format('D')." | ".$date_paid->format('M d, Y').'</td>';
                $body_html .=       '</tr>';
                $total_amount += $row->paid_amount;
                if(count($body['data']) === ++$index){
                    $body_html .=       '<tr>';
                    $body_html .=            '<td></td>';
                    $body_html .=            '<td style="text-align: left; font-weight: bold;">TOTAL:</td>';
                    $body_html .=            '<td style="text-align: left; font-weight: bold;">'.number_format($total_amount, 2).'</td>';
                    $body_html .=            '<td></td>';
                    $body_html .=            '<td></td>';
                    $body_html .=            '<td></td>';
                    $body_html .=       '</tr>';
                }
                $Lno++;
            }

            $body_html .= '     </tbody>';
            $body_html .= ' </table>';
        }

        $mpdf = new mPDF('utf-8','letter'); 
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($body_html);
        $mpdf->Output();
    }
}