<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoanReleased extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_loanReleased');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
    }

    public function index() {
        GET_REQUEST();
        $data['generated_token'] = $this->security->get_csrf_hash();
        $this->load->view('standard_layout.php', $data);
    }

    public function loan_borrower_list(){
        POST_REQUEST();
        $datas = $this->Mdl_loanReleased->loan_borrower_list_table();

        $data = array();
        foreach ($datas as $index => $row) {

            $sub_array = array(
                ++$index,
                ucwords($row->name),
                number_format($row->loan_amount, 2),
                $row->loan_count,
                '<div class="btn-group">
                    <button type="button" class="btn dropdown-toggle bg-purple btn-show-loan-list" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-id="'.encrypt($row->person_info_id).'">
                        MORE
                    </button>
                </div>'
            );

            array_push($data, $sub_array);
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_loanReleased->get_filtered_loan_borrower_data(),  
            "recordsFiltered"   => $this->Mdl_loanReleased->get_all_loan_borrower_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
        
    }

    public function borrower_loan_list() {
        POST_REQUEST();
        $datas = $this->Mdl_loanReleased->borrower_loan_list_table();

        $data = array();
        foreach ($datas as $index => $row) {
            $loan_date = new DateTime($row->loan_date);
            $maturity_date = new DateTime($row->maturity_date); //M d, Y

            $sub_array = array(
                ++$index,
                ucwords($row->name),
                number_format($row->loan_amount, 2),
                $loan_date->format("M d, Y"),
                $maturity_date->format("M d, Y"),
                ucwords($row->status),
                '<div class="btn-group">
                    <button type="button" class="btn dropdown-toggle bg-purple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        MORE <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" class="btn-preview-info" data-id="'.encrypt($row->id).'" >Loan Information</a></li>
                        <li><a href="javascript:void(0);" class="btn-preview-amortization" data-id="'.encrypt($row->id).'" data-plain="'.$row->id.'">Print Amortization Schedule</a></li>
                        <li><a href="javascript:void(0);" class="btn-preview-loan-ledger" data-id="'.encrypt($row->id).'" data-plain="'.$row->id.'">Print Loan Ledger</a></li>
                        <li><a href="javascript:void(0);" class="btn-loan-payment" data-id="'.encrypt($row->id).'" >Loan Ledger</a></li>
                        <!--<li><a href="javascript:void(0);" class="btn-preview-ledger" data-id="'.encrypt($row->id).'" >Ledger</a></li>-->
                        <li><a href="javascript:void(0);" class="btn-preview-transaction-history" data-id="'.encrypt($row->id).'" >Transaction History</a></li>
                    </ul>
                </div>'
            );

            array_push($data, $sub_array);
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_loanReleased->get_filtered_borrower_loan_data(),  
            "recordsFiltered"   => $this->Mdl_loanReleased->get_all_borrower_loan_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
    }

    public function get_borrower_information(){
        POST_REQUEST();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);

        $result = $this->Mdl_loanReleased->get_borrower_information($id);

        if($result['success']){
            $data = $result['data'];
            $loan_date = new DateTime($data->loan_date);
            $maturity_date = new DateTime($data->maturity_date); //M d, Y
            $first_payment_date = new DateTime($data->first_payment_date); //M d, Y

            $response['name'] = ucwords($data->name);
            $response['loan_amount'] = number_format($data->loan_amount, 2);
            $response['frequency_of_payment'] = getLoanFrequencyOfPaymentById($data->frequency_of_payment);
            $response['loan_term'] = $data->loan_term_in_months;
            $response['no_of_payment_period'] = $data->no_of_payment_period;
            $response['loan_date'] = $loan_date->format("M d, Y");
            $response['maturity_date'] = $maturity_date->format("M d, Y");
            $response['first_payment_date'] = $first_payment_date->format("M d, Y");
            $response['deductions'] = $this->get_borrower_deductions($id);
            $response['net_proceed'] = number_format($data->net_proceed, 2);
            $response['tax_amount'] = number_format($data->tax_amount, 2);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = "No data found!";
        }

        echo json_encode($response);
    }

    public function get_borrower_deductions($lbid){
        $result = $this->Mdl_loanReleased->get_borrower_deductions($lbid);
        $data = array();
        $response['deduction_flag'] = false;
        if($result['success']){
            foreach ($result['data'] as $key => $value) {
                $subdata['deduction'] = ucwords(getDeductionDescById($value->ldid));
                $subdata['amortized_flag'] = ($value->amortized_flag ? 1 : 0);
                $subdata['deduct_net_proceed_flag'] = ($value->deduct_net_proceed_flag ? 1 : 0);
                $subdata['rate'] = number_format($value->rate, 2);
                $subdata['amount'] = number_format($value->amount, 2);
                array_push($data, $subdata);
            }
            $response['data'] = $data;
            $response['deduction_flag'] = true;
        }
        return $response;
    }

    public function get_loan_payment_list(){
        POST_REQUEST();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);

        $result = $this->Mdl_loanReleased->get_loan_payment_list($id);

        if($result['success']){
            $data = array();
            $tot_penalty = 0;
            $tot_balance = 0;
            $tot_installment = 0;
            foreach ($result['data'] as $key => $value) {
                $due_date = new DateTime($value->due_date);
                $subdata['id'] = encrypt($value->id);
                $subdata['due_date'] = $due_date->format("M d, Y");
                $subdata['installment_amount'] = number_format($value->installment_amount, 2);
                $subdata['penalty'] = !empty(number_format($value->penalty_amount)) ? $value->penalty_amount : number_format(0, 2);
                $subdata['balance'] = $value->balance_amount !== null ? $value->balance_amount : number_format($value->installment_amount, 2);
                $subdata['option'] = '
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle bg-purple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        MORE <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" class="btn-preview-penalty-details" data-id="'.encrypt($value->id).'" >Penalty Details</a></li>
                        <li><a href="javascript:void(0);" class="btn-preview-due-date-transaction" data-id="'.encrypt($value->id).'" >Payment Transaction</a></li>
                    </ul>
                </div>';
                array_push($data, $subdata);

                $tot_installment += $value->installment_amount;
                $tot_penalty += $value->penalty_amount;
                $tot_balance += !empty($value->balance_amount) ? $value->balance_amount : $value->installment_amount;
            }

            $response['total_installment'] = number_format($tot_installment, 2);
            $response['total_penalty'] = number_format($tot_penalty, 2);
            $response['total_balance'] = number_format($tot_balance, 2);
            $response['success'] = true;
            $response['list'] = $data;
            echo json_encode($response);
        }
    }

    public function get_penalty_list(){
        POST_REQUEST();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);

        $result = $this->Mdl_loanReleased->get_penalty_list($id);

        if($result['success']){
            $data = array();
            foreach ($result['data'] as $key => $value) {
                $date_created = new DateTime($value->date_created);
                $subdata['due_date'] = $date_created->format("M d, Y");
                $subdata['amount'] = $value->amount;
                array_push($data, $subdata);
            }

            $response['success'] = true;
            $response['list'] = $data;
        }else{
            $response['success'] = false;
        }

        echo json_encode($response);
    }

    public function payment_proceed(){
        POST_REQUEST();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $loan_borrower_id = post('loan-borrower-id', true);
        $amount = post('payment-amount', false);

        $errArr = array();
        $errCtr = 0;

        if(empty($loan_borrower_id)){
            array_push($errArr, 'Borrower ID');
            $errCtr++;
        }

        if(empty($amount)){
            array_push($errArr, 'Amount');
            $errCtr++;
        }

        if($errCtr != 0){
            $response['msg'] = implode(', ', $errArr)." is empty!";
            $response['success'] = false;
            echo json_encode($response); exit();
        }

        $response['success'] = false;
        $response['msg'] = "Error while transacting data!";

        $this->db->trans_start();
        $loan_ledger_header_id = $this->Mdl_loanReleased->insert_payment_info($amount, $loan_borrower_id);
        while ($amount != 0) {
            $current_payment_details_result = $this->Mdl_loanReleased->current_payment_details($loan_borrower_id);
            $penalty_paid_amount = 0;
            if($current_payment_details_result['success']){
                $result = $current_payment_details_result['data'];

                if(!empty($result->penalty_balance)){
                    if($amount > $result->penalty_balance){
                        $amount -= $result->penalty_balance;
                        $penalty_paid_amount = $result->penalty_balance;
                    }else{
                        $penalty_paid_amount = $amount;
                        $amount = 0;
                    }
                }

                if(!empty($amount)){
                    if($amount > $result->due_date_balance){
                        $amount -= $result->due_date_balance;
                        $loan_paid_amount = $result->due_date_balance;
                    }else{
                        $loan_paid_amount = $amount;
                        $amount = 0;
                    }
                }

                $payment['loan_ledger_header_id'] = $loan_ledger_header_id;
                $payment['loan_borrower_details_id'] = $result->loan_borrower_details_id;
                $payment['penalty_paid_amount'] = !empty($penalty_paid_amount) ? $penalty_paid_amount : 0;
                $payment['loan_paid_amount'] = !empty($loan_paid_amount) ? $loan_paid_amount : 0;
                $payment['total_paid_amount'] = $payment['penalty_paid_amount'] + $payment['loan_paid_amount'];
                $insert_loan_ledger_detail_response = $this->Mdl_loanReleased->insert_loan_ledger_detail($payment);

                if(empty($amount)){
                    if($insert_loan_ledger_detail_response['success']){

                        $update_approved_member_total_paid_loan_amount_result = $this->Mdl_loanReleased->update_approved_member_total_paid_loan_amount($loan_ledger_header_id, $loan_borrower_id);
                        if($update_approved_member_total_paid_loan_amount_result['success']){
                            $this->db->trans_complete();
                            $response['success'] = true;
                            $response['msg'] = "Payment successfully transacted!";
                        }else{
                            $response['success'] = false;
                            $response['msg'] = "Error while updating total paid amount!";
                        }
                    }else{
                        $response['success'] = false;
                        $response['msg'] = "Error while inserting ledger_details!";
                        echo json_encode($response);
                        exit();
                    }
                }
            }
        }
        
        echo json_encode($response);
    }

    public function get_payment_transaction_details(){
        POST_REQUEST();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);

        $result = $this->Mdl_loanReleased->get_payment_transaction_details($id);

        if($result['success']){
            $data = array();
            foreach ($result['data'] as $key => $value) {
                $paid_date = new DateTime($value->paid_date);
                $sub_array['loan_paid_amount'] = number_format($value->loan_paid_amount, 2);
                $sub_array['penalty_paid_amount'] = number_format($value->penalty_paid_amount, 2);
                $sub_array['total_paid_amount'] = number_format($value->total_paid_amount, 2);
                $sub_array['paid_date'] = $paid_date->format("M d, Y");

                array_push($data, $sub_array);

                $tot_penalty_paid += $value->penalty_paid_amount;
                $tot_loan_paid += $value->loan_paid_amount;
                $tot_paid += $value->total_paid_amount;
            }
            $response['total_penalty_paid'] = number_format($tot_penalty_paid, 2);
            $response['total_loan_paid'] = number_format($tot_loan_paid, 2);
            $response['total_paid'] = number_format($tot_paid, 2);
            $response['success'] = true;
            $response['list'] = $data;
            $response['list_flag'] = true;
        }else{
            $response['success'] = false;
            $response['list_flag'] = false;
        }

        echo json_encode($response);
    }

//Created by darnel --->start here :)

    public function get_loan_history_list(){
        POST_REQUEST();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);

        $result = $this->Mdl_loanReleased->get_loan_history_list($id);

        if($result['success']){
            $data = array();
            foreach ($result['data'] as $key => $value) {
                $paid_date = new DateTime($value->paid_date);
                $sub_array['payment_amount'] = number_format($value->payment_amount, 2);
                $sub_array['paid_date'] = $paid_date->format("M d, Y");
                $sub_array['option'] = '
                                <div class="btn-group">
                                    <button type="button" class="btn btn-loan-history-details bg-purple " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-id="'.encrypt($value->id).'">
                                        MORE
                                    </button>
                                </div>';
                array_push($data, $sub_array);

                $tot_payment += $value->payment_amount;
            }
                $response['total_payment'] = number_format($tot_payment, 2);
                $response['success'] = true;
                $response['list'] = $data;

        }else{
            $response['success'] = false;
        }

        echo json_encode($response);
    }

    public function get_loan_history_details(){
        POST_REQUEST();
        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);

        $result = $this->Mdl_loanReleased->get_loan_history_details($id);

        if ($result['success']) {
            $data = array();
            foreach ($result['data'] as $key => $value) {
                $paid_date = new DateTime($value->paid_date);
                $due_date = new DateTime($value->due_date);
                $sub_array['loan_paid_amount'] = number_format($value->loan_paid_amount, 2);
                $sub_array['penalty_paid_amount'] = number_format($value->penalty_paid_amount, 2);
                $sub_array['total_paid_amount'] = number_format($value->total_paid_amount, 2);
                $sub_array['paid_date'] = $paid_date->format("M d, Y");
                $sub_array['due_date'] = $due_date->format("M d, Y");

                array_push($data, $sub_array);

                $tot_loan += $value->loan_paid_amount;
                $tot_penalty += $value->penalty_paid_amount;
                $tot_paid += $value->total_paid_amount;
            }
                $response['total_loan'] = number_format($tot_loan, 2);
                $response['total_penalty'] = number_format($tot_penalty, 2);
                $response['total_paid'] = number_format($tot_paid, 2);
                $response['success'] = true;
                $response['list'] = $data;
            
        }else{
            $response['success'] = false;
        }

        echo json_encode($response);  
    }

































// printing area #############################################################################################################################

    public function loan_amortization_schedule(){
        $id = get('p');
        $body_html = '';

        $body_header = $this->Mdl_loanReleased->get_loan_release_header($id);
        if($body_header['success']){
            $laon_date = new DateTime($body_header['data']->laon_date);
            $maturity_date = new DateTime($body_header['data']->maturity_date);
            $first_payment_date = new DateTime($body_header['data']->first_payment_date);

        $body_html .= '<table class="table" width="100%;">
                            <tr>
                                <td>Name: '.getPersonFullNameById($body_header['data']->person_info_id).'</td>
                                <td>Loan Date: '.$laon_date->format("m/d/Y").' </td>
                            </tr>
                            <tr>
                                <td>Computation Code: '.strtoupper(getLoanFrequencyOfPaymentById($body_header['data']->frequency_of_payment)).'</td>
                                <td>Maturity Date: '.$maturity_date->format("m/d/Y").' </td>
                            </tr>
                            <tr>
                                <td>Loan Amount: '.number_format($body_header['data']->loan_amount, 2).' </td>
                                <td>1st Payment Date: '.$first_payment_date->format("m/d/Y").' </td>
                            </tr>
                        </table>';
        }

        $body = $this->Mdl_loanReleased->loan_release_details($id);
        if($body['success']){
            $body_html .= '<hr/>
                            <table width="100%;">
                                <thead>
                                    <tr>
                                        <td style="font-weight: bold;">SEQ.</td>
                                        <td style="font-weight: bold;">DUE DATE</td>
                                        <td style="font-weight: bold;">INSTALLMENT</td>
                                        <td style="font-weight: bold;">PRINCIPAL</td>
                                        <td style="font-weight: bold;">DEDUCTIONS</td>
                                        <td style="font-weight: bold;">GRT</td>
                                        <td style="font-weight: bold;">BALANCE</td>
                                    </tr>
                                </thead>
                                <tbody>';
            $total_installment = 0;
            $total_principal = 0;
            $total_interest = 0;
            $total_grt = 0;
            foreach ($body['data'] as $index => $row) {
                $due_date = new DateTime($row->due_date);
                $body_html .=       '<tr>';
                $body_html .=           '<td>'.(++$index).'</td>';
                $body_html .=            '<td>'.$due_date->format('m/d/Y').'</td>';
                $body_html .=            '<td>'.number_format($row->installment_amount, 2).'</td>';
                $body_html .=            '<td>'.number_format($row->principal_amount, 2).'</td>';
                $body_html .=            '<td>'.number_format($row->interest_amount, 2).'</td>';
                $body_html .=            '<td>'.number_format($row->grt_amount, 2).'</td>';
                $body_html .=            '<td>'.number_format($row->balance_amount, 2).'</td>';
                $body_html .=       '</tr>';

                $total_installment += floatval($row->installment_amount);
                $total_principal += floatval($row->principal_amount);
                $total_interest += floatval($row->interest_amount);
                $total_grt += floatval($row->grt_amount);
                if(count($body['data']) === $index){
                    $body_html .=       '<tr>';
                    $body_html .=            '<td></td>';
                    $body_html .=            '<td style="text-align: right; font-weight: bold;">TOTAL:</td>';
                    $body_html .=            '<td style="text-align: left; font-weight: bold;">'.number_format($total_installment, 2).'</td>';
                    $body_html .=            '<td style="text-align: left; font-weight: bold;">'.number_format($total_principal, 2).'</td>';
                    $body_html .=            '<td style="text-align: left; font-weight: bold;">'.number_format($total_interest, 2).'</td>';
                    $body_html .=            '<td style="text-align: left; font-weight: bold;">'.number_format($total_grt, 2).'</td>';
                    $body_html .=            '<td></td>';
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

    public function loan_ledger_report(){
        $id = get('p');
        $body_html = '';

        $body_header = $this->Mdl_loanReleased->get_loan_release_header($id);

            if($body_header['success']){
                $laon_date = new DateTime($body_header['data']->laon_date);
                $maturity_date = new DateTime($body_header['data']->maturity_date);
                $first_payment_date = new DateTime($body_header['data']->first_payment_date);

                    $body_html .= '<table class="table" width="100%;">
                                        <tr>
                                            <td>Name: '.getPersonFullNameById($body_header['data']->person_info_id).'</td>
                                            <td>Loan Date: '.$laon_date->format("m/d/Y").' </td>
                                        </tr>
                                        <tr>
                                            <td>Computation Code: '.strtoupper(getLoanFrequencyOfPaymentById($body_header['data']->frequency_of_payment)).'</td>
                                            <td>Maturity Date: '.$maturity_date->format("m/d/Y").' </td>
                                        </tr>
                                        <tr>
                                            <td>Loan Amount: '.number_format($body_header['data']->loan_amount, 2).' </td>
                                            <td>1st Payment Date: '.$first_payment_date->format("m/d/Y").' </td>
                                        </tr>
                                    </table>';
            }

        $body = $this->Mdl_loanReleased->get_loan_history_detail($id);
            if($body['success']){
                $body_html .= '<hr/>
                                <table width="100%;">
                                    <thead>
                                        <tr>
                                            <td style="font-weight: bold;">SEQ.</td>
                                            <td style="font-weight: bold;">DUE DATE</td>
                                            <td style="font-weight: bold;"></td>
                                            <td style="font-weight: bold;">LOAN PAID AMOUNT</td>
                                            <td style="font-weight: bold;">PENALTY PAID AMOUNT</td>
                                            <td style="font-weight: bold;">PAID DATE</td>
                                        </tr>
                                    </thead>
                                    <tbody>';
                $total_loan_paid_amount = 0;
                $total_penalty_paid_amount = 0;
                $total_interest = 0;
                $total_grt = 0;

                foreach ($body['data'] as $index => $row) {
                    $due_date = new DateTime($row->due_date);
                    $paid_date = new DateTime($row->paid_date);
                    $body_html .=       '<tr>';
                    $body_html .=           '<td>'.(++$index).'</td>';
                    $body_html .=            '<td>'.$due_date->format('m/d/Y').'</td>';
                    $body_html .=            '<td></td>';
                    $body_html .=            '<td>'.number_format($row->loan_paid_amount, 2).'</td>';
                    $body_html .=            '<td>'.number_format($row->penalty_paid_amount, 2).'</td>';
                    $body_html .=            '<td>'.$paid_date->format('m/d/Y').'</td>';
                    $body_html .=       '</tr>';

                $total_loan_paid_amount += floatval($row->loan_paid_amount);
                $total_penalty_paid_amount += floatval($row->penalty_paid_amount);
                    
                    if(count($body['data']) === $index){
                        $body_html .=       '<tr>';
                        $body_html .=            '<br>';
                        $body_html .=            '<td></td>';
                        $body_html .=            '<td></td>';
                        $body_html .=            '<td style="text-align: right; font-weight: bold;">TOTAL:</td>';
                        $body_html .=            '<td style="text-align: left; font-weight: bold;">'.number_format($total_loan_paid_amount, 2).'</td>';
                        $body_html .=            '<td style="text-align: left; font-weight: bold;">'.number_format($total_penalty_paid_amount, 2).'</td>';
                        $body_html .=            '<td>------------------</td>';
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
}
